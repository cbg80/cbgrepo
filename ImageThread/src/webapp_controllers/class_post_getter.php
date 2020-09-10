<?php
/**
 * Declares PostGetterController class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
/**
 * Imports the factory of entity managers
 */
require_once __DIR__ . '/../webapp_model/class_ent_mgr_factory.php';
/**
 * Imports the post manager class PostManagerImpl
 */
require_once __DIR__ . '/../webapp_model/implementations/class_post_manager.php';
/**
 * Imports the class ImageUploadService
 */
require_once __DIR__ .  '/../webapp_utilities/class_img_upload_service.php';
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