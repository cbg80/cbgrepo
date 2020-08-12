<?php
/**
 * Declares ImageUploadTestService class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
namespace tests\webapp_utilities;

/**
 * Imports the ImageUploadService class
 */
require_once __DIR__ . '/../../webapp_utilities/class_img_upload_service.php';

/**
 * Extends ImageUploadService class for testing purposes
 *
 * @package tests\webapp_utilities
 */
final class ImageUploadTestService extends \ImageUploadService
{

    /**
     * Replaces call to move_uploaded_file function by call to rename one so as uploading of image file can be tested
     *
     * @param string $fileName
     * @param string $destination
     * @return bool
     */
    public function moveUploadedFile(string $fileName, string $destination): bool
    {
        return rename($fileName, $destination);
    }
}
