<?php
/**
 * Declares PostExporterControllerr class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// @ /etc/php/php.ini
// extension=zip.so
namespace ImageThread\webapp_controllers;
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
 * Encapsulates methods to manage the dump of all posts
 *
 * @package webapp_controllers
 */
class PostExporterController {
	function exportPosts() {
		$postMgr = EntityManagerFactory::getPostManager ();
		$posts = $postMgr->getPosts ();
		if (is_array ( $posts ) and count ( $posts ) > 0) {
			$pathToCSV = tempnam ( sys_get_temp_dir (), 'itcsv' );
			$ret = file_put_contents ( $pathToCSV, "Title, Filename" . PHP_EOL );
			foreach ( $posts as $post ) {
				$newLine = '"' . $post->imageTitle . '", "' . $post->imageFileName . '"' . PHP_EOL;
				$ret = file_put_contents ( $pathToCSV, $newLine, FILE_APPEND );
			}
			$oldPathToCSV = $pathToCSV;
			$pathToCSV = dirname ( $pathToCSV ) . '/' . basename ( $pathToCSV, '.tmp' ) . '.csv';
			$ret = rename ( $oldPathToCSV, $pathToCSV );
			if (($zipFilePath = ImageUploadService::zipCSVAndImages ( $pathToCSV )) and file_exists ( $zipFilePath )) {
			    $ret = unlink ( $pathToCSV );
			    $zipFileSize = filesize ( $zipFilePath );
			    // header('Content-Description: File Transfer');
			    header ( 'Content-Type: application/zip' );
			    header ( 'Content-Disposition: attachment; filename="posts.zip"' );
			    header ( 'Content-Length: ' . $zipFileSize );
			    header ( 'Pragma: no-cache' );
			    header ( 'Expires: 0' );
			    // header('Cache-Control: must-revalidate');
			    $ret = readfile ( $zipFilePath );
			    unlink ( $zipFilePath );
			    exit ();
			} else {
			    $ret = unlink ( $pathToCSV );
			}
		} else {
			$_REQUEST ['error'] ['code'] = - 8;
			$_REQUEST ['error'] ['message'] = 'No posts to export';
		}
	}
}