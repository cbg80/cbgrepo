<?php
/**
 * Declares APIImageUploadService class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
/**
 * Imports the base class. It is the analog service from the web app
 */
//require_once __DIR__ . '/../../ImageThread/utilities/class_img_upload_service.php';
require_once __DIR__ . '/../webapp_utilities/class_img_upload_service.php';
/**
 * Encapsulates methods to manage the storing and naming of any post image uploaded
 *
 * @package api_utilities
 */
class APIImageUploadService extends ImageUploadService
{
    /*
     * Maximum number of bytes read at a time from the php input stream to store temporarily any post image uploaded
     */
    const bytesAtATime = 7168;
    /**
     * References an instance of RestServer class
     * @var RestServer
     */
    private $_restServer;
    /**
     * 
     * @param RestServer $restServer An instance of RestServer class
     */
    public function __construct(RestServer $restServer)
    {
        $this->_restServer = $restServer;
    }
    /**
     * Returns an array populated with some data from the last post image uploaded via the HTTP PUT method
     * @return NULL|array $$upFileArr
     */
    public function makeArrayOfFileUploaded(): array
    {
        // Creates posts if the request method is PUT and the request content type is not defined. Else it will return 'Not Acceptable' status
        if(($ret1 = RestServer::getRequestMethod()) != 'PUT' or ($ret2 = RestServer::getRequestContentType())) {
            $this->_restServer->response('', 406);
        }
        $upFileArr = array();
        $pathToTmp = tempnam(sys_get_temp_dir(), 'itapif');
        $putData = fopen('php://input', 'r');
        $fp = fopen($pathToTmp, 'w');
        while ($data = fread($putData, self::bytesAtATime)) {
            $ret = fwrite($fp, $data);
        }
        $ret = fclose($putData);
        $ret = fclose($fp);
        $upFileArr['error'] = 0;
        $upFileArr['tmp_name'] = $pathToTmp;
        $upFileArr['size'] = filesize($pathToTmp);
        return $upFileArr;
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
        $retOfMovUpFile = rename($imgUploadTmpName, self::relPathToPostImages . $imgFileNameWithExt);
        if (!$retOfMovUpFile) {
            throw new RuntimeException('Uploaded image cannot be move', -1);
        } else {
            return $imgFileNameWithExt;
        }
    }
    /**
     * Returns TRUE if the image was already uploaded an related to a post. FALSE otherwise
     * @param string $nameOfPostImg Name of the image to look for in the predetermined directory
     * @return bool
     */
    public static function isAlreadyUploaded(string $nameOfPostImg): bool
    {
        if (is_array(($upImgsArr = scandir(self::relPathToPostImages)))) {
            if (in_array($nameOfPostImg, $upImgsArr)) {
                $ret = TRUE;
            } else {
                $ret = FALSE;
            }
        } else {
            print 'ERROR: ' . self::relPathToPostImages . ' no such directory';
            return FALSE;
        }
        return  $ret;
    }
    /**
     * Returns the shared part by any URL of post image
     * @return string
     */
    public static function getStaticOfImgURLs(): string
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . self::absPathToPostImages;
    }
}