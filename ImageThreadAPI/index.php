<?php
error_reporting(E_ALL);
rootAPIRequest();
function rootAPIRequest()
{
	switch ($_REQUEST['action']) {
		case 'doMakePost':///usr/bin/curl -v --upload ./Imágenes/otras/Foto0069.jpg "http://localhost:8080/index.php?action=doMakePost&imgTitle=Molly+jugando"
			require_once __DIR__ . '/api_controllers/class_post_maker.php';
			$apiPostMakerController = new APIPostMakerController();
			$apiPostMakerController->makePost($_REQUEST['imgTitle']);
			break;
		case 'doGetPost':
			require_once __DIR__ . '/api_controllers/class_post_getter.php';
			$apiPostGetterController = new APIPostGetterController();
			$apiPostGetterController->getRequestedPost($_POST['postId']);
			break;
		case 'doGetPosts':
			require_once __DIR__ . '/api_controllers/class_post_getter.php';
			$apiPostGetterController = new APIPostGetterController();
			$apiPostGetterController->getPostsOrderedByTimestamp();
			break;
		case 'doGetTotalPosts':
			require_once __DIR__ . '/api_controllers/class_post_getter.php';
			$apiPostGetterController = new APIPostGetterController();
			$apiPostGetterController->getNumberOfPosts();
			break;
		case 'doGetTotalViews':
			require_once __DIR__ . '/api_controllers/class_post_getter.php';
			$apiPostGetterController = new APIPostGetterController();
			$apiPostGetterController->getNumberOfViews(TRUE);
			break;
		default:
			header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', TRUE, 404);
			exit;
			break;
	}
}