<?php
/**
 * Declares ImageUploadService class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
/**
/**
 * Encapsulates methods to manage the storing and naming of any post image uploaded
 *
 * @package webapp_utilities
 */
class ImageUploadService
{
    /*
     * Relative path to permanent dir where any uploaded post image is stored
     */
    const relPathToPostImages = __DIR__ . '/../assets/img/';
    /*
     * Absolute path from the document root of the web app to permanent dir where any uploaded post image is stored
     */
    const absPathToPostImages = '/assets/img/';
    /*
     * Relative path to file where total number of views of the web page is stored
     */
    const relPathToViewCounterFile = __DIR__ . '/../assets/view_counter.dat';
    //TODO Encapsulate view counter file management in a different service
    /**
     * Returns the sha1 hash of the file whose absolute path is got as the first argument
     * 
     * @param string $absPathToTmpImgFile The absolute temporary path of the last uploaded post image
     * @return string The final name of the last uploaded post image
     */
    public static function getImageName($absPathToTmpImgFile): string
    {
        return sha1_file($absPathToTmpImgFile);
    }
    /**
     * Stores and renames permanently any uploaded post image
     * @param string $imgUploadTmpName Temporary absolute path of the last uploaded post image
     * @param string $imgUploadMimeType Internet media type of the last uploaded post image
     * @throws RuntimeException
     * @return string $imgFileNameWithExt Permanent name of the last uploaded post image along with its extension
     */
    public function moveFile($imgUploadTmpName, $imgUploadMimeType): string
    {
        $imgFileName = self::getImageName($imgUploadTmpName);
        $imgFileNameWithExt = sprintf('%s.%s', $imgFileName, $imgUploadMimeType);
        $retOfMovUpFile = move_uploaded_file($imgUploadTmpName, self::relPathToPostImages . $imgFileNameWithExt);
        if (!$retOfMovUpFile) {
            throw new RuntimeException ( 'Uploaded image cannot be move', - 1 );
        } else {
            return $imgFileNameWithExt;
        }
    }
    /**
     * Deletes the file whose path is got as the first argument
     * @param string $pathToImgFile
     * @return boolean
     */
    public static function removeFileFromDir($pathToImgFile): bool
    {
        return unlink($pathToImgFile);
    }
    /**
     * Packs all post images along with a CSV archive that contains detailed info of all those posts
     * @param string $pathToCSV Path to that CSV archive
     * @return string $return Path to the zip archive that contains all the images along with the CSV file on success. FALSE otherwise
     */
    public static function zipCSVAndImages(string $pathToCSV): string
    {
        chdir(self::relPathToPostImages);
        $zip = new ZipArchive ();
        $zipFileName = 'itzip' . md5 ( uniqid ( basename ( $pathToCSV, '.csv' ), TRUE ) ) . '.zip';
        if (($ret = $zip->open ( $zipFileName, ZipArchive::CREATE ))) {
            foreach ( ($ret = scandir ( '.', SCANDIR_SORT_NONE )) as $value ) {
                if (! in_array ( $value, [
                    '.',
                    '..'
                ] )) {
                    $ret = $zip->addFile ( './' . $value, $value );
                }
            }
            $ret = $zip->addFile ( $pathToCSV, basename ( $pathToCSV ) );
            $ret = $zip->close ();
            $zipFilePath = getcwd()
                         . '/../'
                         . $zipFileName
            ;
            if (rename($zipFileName, $zipFilePath)) {
                $return = $zipFilePath;
            } else {
                $return = FALSE;
            }
        } else {
            $return = FALSE;
        }
        return $return;
    }
    /**
     * Gets and sets how many times the web page has been viewed
     * @param bool $isGotOnly TRUE if it is not required to add one more view of the web page. FALSE otherwise
     * @return string $numberOfViews How many times the web page has been viewed
     */
    public static function getAndSetTotalNumberOfViews(bool $isGotOnly = FALSE): string
    {
        if ($isGotOnly) {
            $fh = fopen(ImageUploadService::relPathToViewCounterFile, 'rb');
            $fhl = flock($fh, LOCK_SH);
            $numberOfViews = fgets($fh);
        } else {
            $fh = fopen(self::relPathToViewCounterFile, 'c+b');
            $fhl = flock($fh, LOCK_EX);
            $numberOfViews = fgets($fh, 10);
            $numberOfViews++;
            $numberOfViews = "$numberOfViews";
            $fhr = rewind($fh);
            $fhw = fwrite($fh, $numberOfViews);
        }
        $fhl = flock ( $fh, LOCK_UN );
        $fhc = fclose ( $fh );
        return $numberOfViews;
    }
}