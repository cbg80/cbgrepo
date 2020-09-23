<?php
/**
 * Declares PostGetterController class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
namespace ImageThread\webapp_controllers;
/**
 * Imports the factory of entity managers
 */
use ImageThread\webapp_model\EntityManagerFactory;
/**
 * Imports the post manager class PostManagerImpl
 */
use ImageThread\webapp_model\implementations\PostManagerImpl;
/**
 * Imports the class ImageUploadService
 */
use ImageThread\webapp_utilities\ImageUploadService;
/**
 * Encapsulates methods to manage the retrieval of any post
 *
 * @package webapp_controllers
 */
class PostGetterController {
	function getPostsOrderedByTimestamp() {
		$postMgr = EntityManagerFactory::getPostManager ();
		$_REQUEST ['posts'] = $postMgr->getPosts ();
	}
	function getRequestedPost($postId) {
		$postMgr = EntityManagerFactory::getPostManager ();
		$_REQUEST ['post'] = $postMgr->getPost ( $postId );
	}
	function getNumberOfPosts() {
		$postMgr = EntityManagerFactory::getPostManager ();
		$_REQUEST ['nOfPosts'] = $postMgr->getNumberOfPosts ();
	}
	function getNumberOfViews(bool $isGotOnly) {
		$_REQUEST ['nOfViews'] = ImageUploadService::getAndSetTotalNumberOfViews ($isGotOnly);
	}
}