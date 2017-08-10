<?php
//require_once __DIR__ . '/../../ImageThread/controllers/class_post_getter.php';
require_once __DIR__ . '/../webapp_controllers/class_post_getter.php';
require_once __DIR__ . '/../api_utilities/class_rest_server.php';
require_once __DIR__ . '/../webapp_utilities/class_img_upload_service.php';
/**
 * Imports the APIImageUploadService class
 */
require_once __DIR__ . '/../api_utilities/class_img_upload_service.php';
class APIPostGetterController extends PostGetterController
{
	function getRequestedPost($postId)
	{
	    $restServer = new RestServer(array(), 'application/json', '', 200);
		// Cross validation if the request method is POST. Else it will return 'Not Acceptable' status
		if ($restServer->getRequestMethod() != 'POST') {
			$restServer->response('', 406);
		}
		if (!isset($postId) or trim($postId) == '') {//If invalid input 'Bad request' status message and reason
		    $restServer->response(RestServer::json(['status' => 'fail', 'message' => 'Undefined post id', 'data' => '']), 400);
		}
		parent::getRequestedPost($postId);
		$responseArray = array();
		if (isset($_REQUEST['post']) and is_object($_REQUEST['post'])) {
		    $post = $_REQUEST['post'];
			$responseArray['status'] = 'success';
			$responseArray['message'] = 'Post got';
			$post->url = APIImageUploadService::getStaticOfImgURLs()
			           . $post->imageFileName
			;
			$responseArray['data'] = $post;
			$responseStatusCode = 302;
		} else {
			$responseArray['status'] = 'fail';
			$responseArray['message'] = 'No such post';
			$responseArray['data'] = '';
			$responseStatusCode = 404;
		}
		$restServer->response(RestServer::json($responseArray), $responseStatusCode);
	}
	function getPostsOrderedByTimestamp()
	{
	    $restServer = new RestServer(array(), 'application/json', '', 200);
		// Cross validation if the request method is GET. Else it will return 'Not Acceptable' status
		if ($restServer->getRequestMethod() != 'GET') {
			$restServer->response('', 406);
		}
		parent::getPostsOrderedByTimestamp();
		$responseArray = array();
		if (isset($_REQUEST['posts']) and is_array($_REQUEST['posts']) and ($numberOfPosts = count($_REQUEST['posts'])) > 0) {
			$responseArray['status'] = 'success';
			$responseArray['message'] = 'Total '
					                  . $numberOfPosts
					                  . ' post(s) found';
			$responseArray['total_records'] = $numberOfPosts;
			$func = function ($post) {
			    $post->url = APIImageUploadService::getStaticOfImgURLs()
			               . $post->imageFileName
			    ;
			    return $post;
			};
			$responseArray['data'] = array_map($func, $_REQUEST['posts']);
			$responseStatusCode = 200;
		} else {
			$responseArray['status'] = 'fail';
			$responseArray['message'] = 'No posts found';
			$responseArray['data'] = '';
			$responseStatusCode = 204;
		}
		$restServer->response(RestServer::json($responseArray), $responseStatusCode);
	}
	function getNumberOfPosts()
	{
	    $restServer = new RestServer(array(), 'application/json', '', 200);
		// Cross validation if the request method is GET. Else it will return 'Not Acceptable' status
		if ($restServer->getRequestMethod() != 'GET') {
			$restServer->response('', 406);
		}
		parent::getNumberOfPosts();
		$responseArray = array();
		if (isset($_REQUEST['nOfPosts']) and ($numberOfPosts = intval($_REQUEST['nOfPosts']))) {
			$responseArray['status'] = 'success';
			$responseArray['message'] = 'Total '
					                  . $numberOfPosts
					                  . ' post(s) found';
			$responseArray['data'] = $numberOfPosts;
			$responseStatusCode = 200;
			$restServer->response(RestServer::json($responseArray), $responseStatusCode);
		} else {
			$responseStatusCode = 204;
			$restServer->response('', $responseStatusCode);
		}
	}
	function getNumberOfViews(bool $isGotOnly)
	{
	    $restServer = new RestServer(array(), 'application/json', '', 200);
		// Cross validation if the request method is GET. Else it will return 'Not Acceptable' status
		if ($restServer->getRequestMethod() != 'GET') {
			$restServer->response('', 406);
		}
		$numberOfViews = ImageUploadService::getAndSetTotalNumberOfViews($isGotOnly);
		if (($numberOfViews = intval($numberOfViews))) {
		    $responseArray = array();
			$responseArray['status'] = 'success';
			$responseArray['message'] = 'Total '
					                  . $numberOfViews
					                  . ' view(s)';
			$responseArray['data'] = $numberOfViews;
			$responseStatusCode = 200;
			$restServer->response(RestServer::json($responseArray), $responseStatusCode);
		} else {
			$responseStatusCode = 204;
			$restServer->response('', $responseStatusCode);
		}
	}
}