<?php
/**
 * Declares a resource uploader service
 * 
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// src/AppBundle/Service/FileUploader.php
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Encapsulates the resource upload logic        
 */
class FileUploader
{
    private
        /**
         * Path to the folder where any uploaded resource is stored
         * @var string
         */
        $targetDir
    ;
    /**
     * Sets the path to the folder where any uploaded resource is stored
     * @param string $targetDir
     */
    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }
    /**
     * Stores and renames permanently any uploaded resource
     * @param UploadedFile $file Last uploaded resource
     * @throws FileException If the last uploaded resource cannot be moved or the new resource cannot be created
     * @return string $fileName A name along with an extension for the brand new resource
     */
    public function upload(UploadedFile $file)
    {
        $fileName = sha1_file($file->getPathname());
        $fileName = sprintf('%s.%s', $fileName, $file->guessExtension());
        try {
            $file->move($this->targetDir, $fileName);
        } catch (FileException $ex) {
            throw $ex;
        }
        return $fileName;
    }
    /**
     * @return string $targetDir
     */
    public function getTargetDir() : string
    {
        return $this->targetDir;
    }
}