<?php

/**
 * Declares ImageUploadValidator class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */

/**
 * Encapsulates methods to validate the file of any post image uploaded
 *
 * @package webapp_utilities
 */
// @ php.ini
// upload_max_filesize = 20M
// post_max_size = 40M
class ImageUploadValidator {
	private $_imgUploadInfo;
	private $_mimeType;
	private static $_allowedMimeTypes = [ 
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'png' => 'image/png' 
	];
	const maxWidth = 1920;
	const maxHeight = 1080;
	const maxWeigth = 20971520;
	function __construct(array $imgUploadInfo)
	{
	    $this->_imgUploadInfo = $imgUploadInfo;
	}
	function getImgUploadInfo() {
		return $this->_imgUploadInfo;
	}
	function getMimeType() {
		return $this->_mimeType;
	}
	static function getAllowedMimeTypes() {
		return self::$_allowedMimeTypes;
	}
    function checkIfFileIsCorrupted() {
		if (! isset ( $this->_imgUploadInfo ['error'] ) or is_array ( $this->_imgUploadInfo ['error'] )) {
			throw new RuntimeException ( 'Uploaded image corrupted', - 7 );
		}
	}
	function checkFileErrorValue() {
		switch ($this->_imgUploadInfo ['error']) {
			case UPLOAD_ERR_OK :
				break;
			case UPLOAD_ERR_INI_SIZE :
				$message = 'Size of uploaded image exceeds server side limit';
				$code = UPLOAD_ERR_INI_SIZE;
				break;
			case UPLOAD_ERR_FORM_SIZE :
				$message = 'Size of uploaded image exceeds client side limit';
				$code = UPLOAD_ERR_FORM_SIZE;
				break;
			case UPLOAD_ERR_PARTIAL :
				$message = 'Partially uploaded image';
				$code = UPLOAD_ERR_PARTIAL;
				break;
			case UPLOAD_ERR_NO_FILE :
				$message = 'No image uploaded';
				$code = UPLOAD_ERR_NO_FILE;
				break;
			case UPLOAD_ERR_NO_TMP_DIR :
				$message = 'tmp folder is missed';
				$code = UPLOAD_ERR_NO_TMP_DIR;
				break;
			case UPLOAD_ERR_CANT_WRITE :
				$message = 'Uploaded image cannot be written to disk';
				$code = UPLOAD_ERR_CANT_WRITE;
				break;
			case UPLOAD_ERR_EXTENSION :
				$message = 'Upload stopped by server side extension';
				$code = UPLOAD_ERR_EXTENSION;
				break;
			default :
				$message = 'Unknown upload error';
				$code = '-6';
				break;
		}
		if (isset ( $message, $code )) {
			throw new RuntimeException ( $message, $code );
		}
	}
	function checkFileSize() {
		list ( $width, $height ) = getimagesize ( $this->_imgUploadInfo ['tmp_name'] );
		if ($width > self::maxWidth) {
			$message = 'Width of uploaded image exceeds server side limit';
			$code = - 5;
		} elseif ($height > self::maxHeight) {
			$message = 'Height of uploaded image exceeds server side limit';
			$code = - 4;
		}
		if (isset ( $message, $code )) {
			throw new RuntimeException ( $message, $code );
		}
	}
	function checkFileWeigth() {
		if ($this->_imgUploadInfo ['size'] > self::maxWeigth) {
			throw new RuntimeException ( 'Weigth of uploaded image exceeds server side limit', - 3 );
		}
	}
	function checkFileMimeType() {
		$finfo = new finfo ( FILEINFO_MIME_TYPE );
		if (($this->_mimeType = array_search ( $finfo->file ( $this->_imgUploadInfo ['tmp_name'] ), self::$_allowedMimeTypes, TRUE )) === FALSE) {
			throw new RuntimeException ( 'Not allowed mime type', - 2 );
		}
	}
}
?>