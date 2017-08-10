<?php
/**
 * Declares APIPostMakerController class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
/**
 * Imports the base class. It is the controller from the web app
 */
//require_once __DIR__ . '/../../ImageThread/controllers/class_post_maker.php';
require_once __DIR__ . '/../webapp_controllers/class_post_maker.php';
/**
 * Imports the APIImageUploadService class
 */
require_once __DIR__ . '/../api_utilities/class_img_upload_service.php';
/**
 * Imports the RestServer class
 */
require_once __DIR__ . '/../api_utilities/class_rest_server.php';
/**
 * Imports the ImageUploadValidator class
 */
require_once __DIR__ . '/../webapp_utilities/class_img_upload_validator.php';
/**
 * Imports the APIEntityManagerFactory class
 */
require_once __DIR__ . '/../api_model/class_ent_mgr_factory.php';
/**
 * Encapsulates methods to manage the creation of brand new posts via the API server
 *
 * @package api_controllers
 */
class APIPostMakerController extends PostMakerController
{
    /**
     * 
     * {@inheritDoc}
     * @see PostMakerController::makePost()
     */
    function makePost(string $imageTitle, array $imgUpInfoArr = array(), $imgUpService = NULL, $imgUpValidator = NULL)
	{
	    $restServer = new RestServer(array(), 'application/json', '', 200);
	    if (!isset($imageTitle) or trim($imageTitle) == '') {//If invalid input 'Bad request' status message and reason
	        $restServer->response(RestServer::json(['status' => 'fail', 'message' => 'Undefined image title', 'data' => '']), 400);
	    }
	    $imgUpService  = new APIImageUploadService($restServer);
	    $imgUpInfoArr = $imgUpService->makeArrayOfFileUploaded();
	    $nameOfPostImg = ImageUploadService::getImageName($imgUpInfoArr['tmp_name']);
	    $imgUpValidator = new ImageUploadValidator($imgUpInfoArr);
	    $imgUpValidator->checkFileMimeType();
	    $nameOfPostImg = $nameOfPostImg
	                   . '.'
	                   . $imgUpValidator->getMimeType()
	    ;
	    $responseArray = array();
	    if (APIImageUploadService::isAlreadyUploaded($nameOfPostImg)) {
	        $imgIsAlreadyUploaded = TRUE;
	        $apiPostMgr = APIEntityManagerFactory::getPostManager();
	        $post = $apiPostMgr->getPostByImg($nameOfPostImg, $imageTitle);
	        $ret = $apiPostMgr->updatePost($post);
	    } else {
	        $imgIsAlreadyUploaded = FALSE;
	        $ret = parent::makePost($imageTitle, $imgUpInfoArr, $imgUpService, $imgUpValidator);
		}
		if ($ret === FALSE) {
			$responseArray['status'] = 'fail';
			if (isset($_REQUEST['error'])) {
				$ret = ImageUploadService::removeFileFromDir($imgUpInfoArr['tmp_name']);
				$responseArray['message'] = $_REQUEST['error']['message'];
				switch ($_REQUEST['error']['code']) {#http://www.ietf.org/rfc/rfc2616.txt
					case -5:
					case -4:
					case -3:
						$responseStatusCode = 413;
						break;
					case -2:
						$responseStatusCode = 415;
						break;
					case -1:
// 						$responseStatusCode = 304;
// 						http://php.net/manual/es/function.ob-gzhandler.php#97385
						$responseStatusCode = 409;
						break;
					default:
						$responseStatusCode = 412;
						break;
				}
			} else {
			    if ($imgIsAlreadyUploaded) {
			        $responseArray['message'] = 'Updating post KO';
			    } else {
			        $responseArray['message'] = 'Creating post KO';
			    }
				$responseStatusCode = 500;
			}
			$responseArray['data'] = '';
			$restServer->response(RestServer::json($responseArray), $responseStatusCode);
		} else {
		    if ($imgIsAlreadyUploaded) {
		        $ret = ImageUploadService::removeFileFromDir($imgUpInfoArr['tmp_name']);
		        $responseArray['message'] = 'Updating post OK';
		        $responseArray['data']['id'] = $post->getId();
		    } else {
		        $responseArray['message'] = 'Creating post OK';
		        $responseArray['data']['id'] = $ret;
		    }
		    $urlOfUpImg = APIImageUploadService::getStaticOfImgURLs()
		                . $nameOfPostImg
		    ;
		    $responseArray['data']['url'] = $urlOfUpImg;
		    $responseArray['status'] = 'success';
		    $restServer->response(RestServer::json($responseArray), 201);
		}
	}
}