<?php
/**
 * Declares the Post form type
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// src/AppBundle/Form/PostType.php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Post;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Encapsulates the logic for generating the Post form type
 */
class PostType extends AbstractType
{
    /**
     * Builds the form of type Post
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'required' => FALSE
            , 'label' => 'Post title: '
            , 'attr' => array(
                'maxlength' => 255
                , 'placeholder' => "short picture's description"
            )
        ))
        ->add('image', FileType::class, array(
            'label' => 'Post image: '
            , 'attr' => array(
                'accept' => implode(', ', $options['uploaded_resources_allowed_mime_types'])
                , 'title' => 'Only ' . implode(', ', $options['uploaded_resources_allowed_mime_types']) . ' accepted'
            )
            , 'constraints' => array(
                ($imgConstraint = new Image(array(
                    'mimeTypes' => $options['uploaded_resources_allowed_mime_types']
                    , 'maxWidth' => $options['uploaded_resources_max_width']
                    , /*Already checked*/'maxHeight' => $options['uploaded_resources_max_height']
                    , 'detectCorrupted' => TRUE
                    , 'maxSize' => $options['uploaded_resources_max_weigth']
                    )
                ))
            )
        ))
        ->add('MAX_FILE_SIZE', HiddenType::class, array('data' => $options['uploaded_resources_max_weigth']
            , 'mapped' => FALSE
        ))
        ->add('Reply', SubmitType::class)
        ;
        // TODO - Declare the following code inside an event subscriber
        $builder/*->get('image')*/->addEventListener(FormEvents::POST_SUBMIT
            , function (FormEvent $event) use ($imgConstraint) {
                /* @var $post Post */
                $post = $event->getForm()/*->getParent()*/->getData();
                
                $imgConstraint->mimeTypesMessage = $post->getImage()->getMimeType() . ' is not an allowed mime type for uploaded resource';
                $imgConstraint->corruptedMessage = 'The image file ' . $post->getImage()->getBasename() . '.' . $post->getImage()->getExtension() . ' is corrupted';
        });
    }
    /**
     * Sets up the config required to build the form of type Post.
     * {@inheritDoc}
     * @see \Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => Post::class));
        $resolver->setRequired(array(
            'uploaded_resources_allowed_mime_types'
            , 'uploaded_resources_max_weigth'
            , 'uploaded_resources_max_width'
            , 'uploaded_resources_max_height'
        ));
    }
}