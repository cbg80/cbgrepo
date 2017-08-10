<?php
/**
 * Declares Post class
 * 
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
/**
 * Encapsulates methods to set and get any property of any post
 * 
 * @package webapp_model
 * 
 * @subpackage entities
 */
class Post {
	/**
	 * Unique identifier of any post
	 * 
	 * @var int
	 */
	private $id;
	/**
	 * Brief description of any post
	 * 
	 * @var string
	 */
	private $imageTitle;
	/**
	 * File name of any post image
	 * 
	 * @var string
	 */
	private $imageFileName;
	/**
	 * Timestamp of any post
	 * 
	 * @var string
	 */
	private $timestamp;
	/**
	 * Class constructor
	 * 
	 * @param string $imageTitle Brief description of the post of context
	 * @param string $imageFileName File name of the image of the post of context
	 * @param string $timestamp Timestamp of the post of context
	 * @param int $id Unique indentifier of the post of context
	 */
	function __construct($imageTitle, $imageFileName, $timestamp, $id = NULL) {
		$this->id = $id;
		$this->imageFileName = $imageFileName;
		$this->imageTitle = $imageTitle;
		$this->timestamp = $timestamp;
	}
	/**
	 * Gets the unique identifier of the post of context
	 * 
	 * @return int
	 */
	function getId() {
		return $this->id;
	}
	/**
	 * Gets a brief description of the post of context
	 * 
	 * @return string
	 */
	function getImageTitle() {
		return $this->imageTitle;
	}
	/**
	 * Gets the file name of the imgage of the post of context
	 * 
	 * @return string
	 */
	function getImageFileName() {
		return $this->imageFileName;
	}
	/**
	 * Gets the timestamp of the post of context
	 *  
	 * @return string
	 */
	function getTimestamp() {
		return $this->timestamp;
	}
}
?>