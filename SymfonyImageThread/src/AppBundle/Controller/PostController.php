<?php
/**
 * Declares the Post controller
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// src/AppBundle/Controller/PostController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Service\CSVExporter;
use AppBundle\Service\ZipArchiver;
use Symfony\Component\HttpFoundation\Response;

/**
 * Encapsulates the action controllers
 */
class PostController extends Controller
{    
    /**
     * Returns the form used to export all the so far registered posts as a CSV file
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getExportingForm()
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('post_exporter'))
        ->setMethod('GET')
        ->add('Export', SubmitType::class)
        ->getForm()
        ;
    }
    /**
     * Manages the retrieval of the Post exporting form, total number of posts registered and those posts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllAction()
    {
        /** @var \AppBundle\Repository\PostRepository $postRepository */
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $totalPosts = $postRepository->countTotals();
        
        if ($totalPosts == 0) {
            throw  $this->createNotFoundException('No posts found');
        }
        
        $exportingForm = $this->getExportingForm();
        
        $posts = $postRepository->getAll();
        
        return $this->render('post/getall.html.twig', array(
            'form' => $exportingForm->createView()
            , 'totals' => $totalPosts
            , 'posts' => $posts
            // TODO - Set up following param in config files
            , 'upload_dir' => basename($this->getParameter('uploaded_resources_dir'))
        ));
    }
    /**
     * Manages the export of so far registered post images along with a CSV file holding info about those posts as a ZIP archive
     * @param FormInterface $exportingForm
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param CSVExporter $csvExporter
     * @param ZipArchiver $zipArchiver
     * @param Response $response
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function exportAction(EntityManagerInterface $em, Request $request, CSVExporter $csvExporter, ZipArchiver $zipArchiver)
    {
        if (!($arrayExportingForm = $request->query->get('form')) or !isset($arrayExportingForm['_token']) or $arrayExportingForm['_token'] == '') {
            return $this->redirectToRoute('post_allgetter');
        }
        
        $posts = $em->getRepository(Post::class)->findAll();
        
        if (!is_array($posts) or count($posts) == 0) {
            throw $this->createNotFoundException('No posts found');
        }
        
        $csvFileObject = $csvExporter->export($posts, $request->getSchemeAndHttpHost(), basename($this->getParameter('uploaded_resources_dir')));
        
        $zipArchivePath = $zipArchiver->archive($csvFileObject);
        $zipArchiveSize = filesize($zipArchivePath);

        $response = new Response();
        //$response->headers->set('Content-Description', 'File Transfer'))
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment; filename="posts.zip"');
        $response->headers->set('Content-Length', $zipArchiveSize);
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        //$response->headers->set('Cache-Control', 'must-revalidate');
        
        $response->setContent(file_get_contents($zipArchivePath));
        
        unlink($zipArchivePath);
        
        return $response;
    }
    /**
     * Manages the creation of brand new posts
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function makeAction(EntityManagerInterface $em, Request $request)
    {
        $post = new Post();
        
        $makingForm = $this->createForm(PostType::class, $post, array(
            'uploaded_resources_allowed_mime_types' => $this->getParameter('uploaded_resources_allowed_mime_types')
            , 'uploaded_resources_max_weigth' => $this->getParameter('uploaded_resources_max_weigth')
            , 'uploaded_resources_max_width' => $this->getParameter('uploaded_resources_max_width')
            , 'uploaded_resources_max_height' => $this->getParameter('uploaded_resources_max_height')
        ));
        
        $makingForm->handleRequest($request);
        
        if ($makingForm->isSubmitted() and $makingForm->isValid()) {
            $em->persist($post);
            $em->flush();
            
            return $this->redirectToRoute('post_allgetter');
        }
        
        return $this->render('post/make.html.twig', array('form' => $makingForm->createView()));
    }
}