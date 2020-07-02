<?php
/**
 * The front controller of the app
 * 
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
error_reporting(E_ALL);
rootRequest();

function rootRequest()
{
    if (in_array($_REQUEST['action'], [
        'doGetPosts',
        'doMakePost'
    ])) {
        $getPostsFunc = function (bool $isGotOnly) {
            require_once __DIR__ . '/class_post_getter.php';
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
            require_once __DIR__ . '/class_post_maker.php';
            $postMakerController = new PostMakerController();
            $ret = $postMakerController->makePost($_POST['imgTitle'], $_FILES['imgFile']);
            $ret = call_user_func($getPostsFunc, TRUE);
            break;
        case 'doExportPosts':
            require_once __DIR__ . '/class_post_exporter.php';
            $postExporterController = new PostExporterController();
            $postExporterController->exportPosts();
            break;
        default:
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', TRUE, 404);
            exit();
            break;
    }
}