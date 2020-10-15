<?php

/**
 * Declares ImageUploadValidator class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// @ php.ini
// upload_max_filesize = 2M
// post_max_size = 8M
namespace ImageThread\webapp_utilities;
/**
 * Encapsulates methods to validate the file of any post image uploaded
 *
 * @package webapp_utilities
 */
class ImageUploadValidator
{

    /**
     * It stores $_FILES ['imgFile']
     *
     * @var array
     */
    private $_imgUploadInfo;

    /**
     * Regarding the internet media type held by $_imgUploadInfo['tmp_name'], it stores the key of the match
     * found in $_allowedMimeTypes if any.
     * Otherwise, FALSE
     *
     * @var string
     */
    private $mimeType;

    /**
     * It stores the allowed internet media types for $_imgUploadInfo['tmp_name'] to hold
     *
     * @var array
     */
    private static $_allowedMimeTypes = [
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'png' => 'image/png'
    ];

    const maxWidth = 1920;

    const maxHeight = 1080;

    const maxWeigth = 2097152;

    /**
     * Class constructor
     *
     * @param array $imgUploadInfo
     */
    function __construct(array $imgUploadInfo)
    {
        $this->_imgUploadInfo = $imgUploadInfo;
    }

    /**
     * Gets $_imgUploadInfo
     *
     * @return array
     */
    function getImgUploadInfo(): array
    {
        return $this->_imgUploadInfo;
    }

    /**
     * Gets $mimeType
     *
     * @return string
     */
    function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Gets $_allowedMimeTypes
     *
     * @return array
     */
    static function getAllowedMimeTypes(): array
    {
        return self::$_allowedMimeTypes;
    }

    /**
     * Checks if $_imgUploadInfo['error'] holds data
     *
     * @throws \RuntimeException
     */
    function checkIfFileIsCorrupted()
    {
        if (! isset($this->_imgUploadInfo['error']) or is_array($this->_imgUploadInfo['error'])) {
            throw new \RuntimeException(IMG_THREAD_UPLOAD_ERR_CORRUPTED['message'], IMG_THREAD_UPLOAD_ERR_CORRUPTED['code']);
        }
    }

    /**
     * Parses the info held by $_imgUploadInfo['error']
     *
     * @throws \RuntimeException
     */
    function checkFileErrorValue()
    {
        switch ($this->_imgUploadInfo['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
                $message = 'Size of uploaded image exceeds server side limit';
                $code = UPLOAD_ERR_INI_SIZE;
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = 'Size of uploaded image exceeds client side limit';
                $code = UPLOAD_ERR_FORM_SIZE;
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = 'Partially uploaded image';
                $code = UPLOAD_ERR_PARTIAL;
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = 'No image uploaded';
                $code = UPLOAD_ERR_NO_FILE;
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = 'tmp folder is missed';
                $code = UPLOAD_ERR_NO_TMP_DIR;
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = 'Uploaded image cannot be written to disk';
                $code = UPLOAD_ERR_CANT_WRITE;
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = 'Upload stopped by server side extension';
                $code = UPLOAD_ERR_EXTENSION;
                break;
            default:
                $message = IMG_THREAD_UPLOAD_ERR_UNKNOWN['message'];
                $code = IMG_THREAD_UPLOAD_ERR_UNKNOWN['code'];
                break;
        }
        if (isset($message, $code)) {
            throw new \RuntimeException($message, $code);
        }
    }

    /**
     * Parses the size data held by $_imgUploadInfo['tmp_name']
     *
     * @throws \RuntimeException
     */
    function checkFileSize()
    {
        list ($width, $height) = getimagesize($this->_imgUploadInfo['tmp_name']);
        if ($width > self::maxWidth) {
            $message = IMG_THREAD_UPLOAD_ERR_WIDER['message'];
            $code = IMG_THREAD_UPLOAD_ERR_WIDER['code'];
        } elseif ($height > self::maxHeight) {
            $message = IMG_THREAD_UPLOAD_ERR_HIGHER['message'];
            $code = IMG_THREAD_UPLOAD_ERR_HIGHER['code'];
        }
        if (isset($message, $code)) {
            throw new \RuntimeException($message, $code);
        }
    }

    /**
     * Parses the weigth data stored in $_imgUploadInfo['size']
     *
     * @throws \RuntimeException
     */
    function checkFileWeigth()
    {
        if ($this->_imgUploadInfo['size'] > self::maxWeigth) {
            throw new \RuntimeException(IMG_THREAD_UPLOAD_ERR_HEAVIER['message'], IMG_THREAD_UPLOAD_ERR_HEAVIER['code']);
        }
    }

    /**
     * Parses the internet media type data held by $_imgUploadInfo['tmp_name']
     *
     * @throws \RuntimeException
     */
    function checkFileMimeType()
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        if (($this->mimeType = array_search($finfo->file($this->_imgUploadInfo['tmp_name']), self::$_allowedMimeTypes, TRUE)) === FALSE) {
            throw new \RuntimeException(IMG_THREAD_UPLOAD_ERR_FORBIDDEN['message'], IMG_THREAD_UPLOAD_ERR_FORBIDDEN['code']);
        }
    }
}
?>