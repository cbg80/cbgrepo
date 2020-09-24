<?php
/**
 * Declares PostMakerController class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
namespace ImageThread\webapp_controllers;
/**
 * Imports the php bean for any post
 */
use ImageThread\webapp_model\entities\Post;
/**
 * Imports the factory of entity managers
 */
use ImageThread\webapp_model\EntityManagerFactory;
/**
 * Imports the post manager class PostManagerImpl
 */
use ImageThread\webapp_model\implementations\PostManagerImpl;
/**
 * Imports the ImageUploadValidator class
 */
use ImageThread\webapp_utilities\ImageUploadValidator;
/**
 * Imports the ImageUploadService class
 */
use ImageThread\webapp_utilities\ImageUploadService;
/**
* Encapsulates methods to manage the creation of brand new posts
*
* @package webapp_controllers
*/
class PostMakerController {
    function makePost(string $imageTitle, array $imgUpInfoArr, $imgUpService = NULL, $imgUpValidator = NULL) {
        if (!isset($imgUpService)) {
            $imgUpService  = new ImageUploadService();
        }
        if (!isset($imgUpValidator)) {
            $imgUpValidator = new ImageUploadValidator($imgUpInfoArr);
        }
		try {
			$imgUpValidator->checkIfFileIsCorrupted ();
			$imgUpValidator->checkFileErrorValue ();
			$imgUpValidator->checkFileMimeType ();
			$imgUpValidator->checkFileSize ();
			$imgUpValidator->checkFileWeigth ();
			$imgPermName = $imgUpService->moveFile($imgUpInfoArr['tmp_name'], $imgUpValidator->getMimeType());
		} catch ( \RuntimeException $ex ) {
			$_REQUEST ['error'] ['code'] = $ex->getCode ();
			$_REQUEST ['error'] ['message'] = $ex->getMessage ();
			return FALSE;
		}
		// @my.cnf
		// [mysqld]
		// ...
		// sql_mode = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'
		$post = new Post ( $imageTitle, $imgPermName, date ( 'Y-m-d H:i:s' ) );
		// $post = new Post($imageTitle, $imgPermName, 1234);
		$postMgr = EntityManagerFactory::getPostManager ();
		if (! ($ret = $postMgr->createPost ( $post ))) {
		    $pathToImgFile = ABS_PATH_TO_POST_IMG
		                   . $imgPermName
		    ;
			$return = ImageUploadService::removeFileFromDir($pathToImgFile);
		}
		return $ret;
	}
}