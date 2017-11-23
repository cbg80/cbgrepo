<?php
/**
 * Declares a Doctrine listener
 * 
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// src/AppBundle/EventListener/ImageUploadListener.php
namespace AppBundle\EventListener;

use AppBundle\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Post;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Encapsulates methods to automatically upload any post image when persisting the post entity
 */
class ImageUploadListener
{
    /**
     * A FileUploader object
     * @var FileUploader
     */
    private $uploader;
    /**
     * Sets the $uploader property
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }
    /**
     * Uploads the file when persisting the entity
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }
    /**
     * Only uploads new files and sets the property of the entity holding the name of the uploaded file
     * @param Post $entity
     */
    private function uploadFile($entity)
    {
        if (!$entity instanceof Post) {
            return;
        }
        $file = $entity->getImage();
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setImage($fileName);
            $entity->setTimestamp(new \DateTime());
        }
    }
    /**
     * Creates the File instance based on the path when fetching entities from the database
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Post) {
            return;
        }
        if ($fileName = $entity->getImage()) {
            $entity->setImage(new File($this->uploader->getTargetDir() . '/' . $fileName));
        }
    }
}