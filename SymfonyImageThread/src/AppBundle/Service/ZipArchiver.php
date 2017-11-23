<?php
/**
 * Declares a service for archiving the contents of a predefined folder along with optional content as ZIP
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// src/AppBundle/Service/ZipArchiver.php
namespace AppBundle\Service;

/**
 * Encapsulates a method for archiving the contents of a predefined folder along with optional content as ZIP
 */
class ZipArchiver
{
    /**
     * Path to the folder whose content is supposed to be archived as ZIP
     * @var string
     */
    private $targetDir;
    /**
     * Sets the path to the folder whose content is supposed to be archived as ZIP
     * @param string $targetDir
     */
    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }
    /**
     * Archive the contents of a predefined folder along with optional content as ZIP
     * @param unknown $optionalContent
     * @return string The absolute path to the ZIP archive
     */
    public function archive($optionalContent = NULL)
    {
        chdir(sys_get_temp_dir());
        
        $zip = new \ZipArchive();
        $zipArchiveName = tempnam(sys_get_temp_dir(), 'postzip');
        
        if (($return = $zip->open($zipArchiveName, \ZipArchive::CREATE))) {
            if (isset($optionalContent)) {
                /* @var $optionalContent Symfony\Component\HttpFoundation\File\File */
                $optionalContentPath = $optionalContent->getRealPath();
                $return = $zip->addFile($optionalContentPath, basename($optionalContentPath));
                unlink($optionalContentPath);
            }
            foreach (($return = scandir($this->targetDir, SCANDIR_SORT_NONE)) as $postImage) {
                if (!in_array($postImage, ['.', '..'])) {
                    $return[$postImage] = $zip->addFile($this->targetDir . '/' . $postImage, $postImage);
                }
            }
            $return = $zip->close();
            return $zipArchiveName;
        }
    }
}