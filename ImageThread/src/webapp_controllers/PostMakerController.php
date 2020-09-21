<?php
/**
 * Declares PostMakerController class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
namespace ImageThread\webapp_controllers;
/**
 * Imports the php bean for any post
 */
require_once __DIR__ . '/../webapp_model/entities/class_post.php';
/**
 * Imports the factory of entity managers
 */
require_once __DIR__ . '/../webapp_model/class_ent_mgr_factory.php';
/**
 * Imports the post manager class PostManagerImpl
 */
require_once __DIR__ . '/../webapp_model/implementations/class_post_manager.php';
/**
 * Imports the ImageUploadValidator class
 */
require_once __DIR__ . '/../webapp_utilities/class_img_upload_validator.php';
/**
 * Imports the ImageUploadService class
 */
require_once __DIR__ . '/../webapp_utilities/class_img_upload_service.php';
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
		} catch ( RuntimeException $ex ) {
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