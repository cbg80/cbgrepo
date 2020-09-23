<?php
/**
 * The front controller of the app
 * 
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
/**
 * Loads composer default autoloader
 */
require_once __DIR__ . '/../autoload.php';

use ImageThread\webapp_controllers\PostGetterController;
use ImageThread\webapp_controllers\PostMakerController;
use ImageThread\webapp_controllers\PostExporterController;

error_reporting(E_ALL);
rewriteRequest();
rootRequest();

function rootRequest()
{
    if (in_array($_REQUEST['action'], [
        'doGetPosts',
        'doMakePost'
    ])) {
        $getPostsFunc = function (bool $isGotOnly) {
            $postGetterController = new PostGetterController();
            $postGetterController->getNumberOfViews($isGotOnly);
            $postGetterController->getNumberOfPosts();
            $postGetterController->getPostsOrderedByTimestamp();
            require_once __DIR__ . '/../views/header.php';
            require_once __DIR__ . '/../views/top_bar.php';
            require_once __DIR__ . '/../views/reply_box.php';
            require_once __DIR__ . '/../views/img_post_boxes.php';
            require_once __DIR__ . '/../views/footer.php';
        };
    }
    switch ($_REQUEST['action']) {
        case 'doGetPosts':
            $ret = call_user_func($getPostsFunc, FALSE);
            break;
        case 'doMakePost':
            $postMakerController = new PostMakerController();
            $ret = $postMakerController->makePost($_POST['imgTitle'], $_FILES['imgFile']);
            $ret = call_user_func($getPostsFunc, TRUE);
            break;
        case 'doExportPosts':
            $postExporterController = new PostExporterController();
            $postExporterController->exportPosts();
            break;
        default:
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', TRUE, 404);
            exit();
            break;
    }
}

function rewriteRequest()
{
    if (!isset($_REQUEST['action']) && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_TYPE']) && 
        strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== FALSE) {
            $_REQUEST['action'] = 'doMakePost';
            $_POST['imgTitle'] = '';
            $_FILES['imgFile']['error'] = UPLOAD_ERR_INI_SIZE;
        }
}