<?php
/**
 * Declares PostMakerControllerTest class
 * 
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
namespace ImageThreadTests\webapp_controllers;

/**
 * Imports the factory of entity managers
 */
use ImageThread\webapp_model\EntityManagerFactory;
/**
 * Imports the post manager class PostManagerImpl
 */
use ImageThread\webapp_model\implementations\PostManagerImpl;
/**
 * Imports the PostMakerController class
 */
use ImageThread\webapp_controllers\PostMakerController;
/**
 * Imports the php bean for any post
 */
use ImageThread\webapp_model\entities\Post;
/**
 * Imports the ImageUploadValidator class
 */
use ImageThread\webapp_utilities\ImageUploadValidator;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use ImageThreadTests\webapp_utilities\ImageUploadTestService;
use ImageThreadTests\data_providers\PostMakerControllerDataProvider;

/**
 * Encapsulates methods to test the creation of brand new posts
 *
 * @package tests\webapp_controllers
 */
class PostMakerControllerTest extends TestCase
{

    /**
     * Test some error use cases while trying to create brand new posts
     *
     * @param array $fileUpInfoArr
     * @param array $exPropertyValuesArr
     * @dataProvider ImageThreadTests\data_providers\PostMakerControllerDataProvider::makePostFaultProvider
     */
    public function testMakePostFault(array $fileUpInfoArr, array $exPropertyValuesArr)
    {
        $fileName = $fileUpInfoArr['name'];
        $srcPath = __DIR__ . '/../localdir/' . $fileName;
        $tmpPath = $fileUpInfoArr['tmp_name'];
        if ($tmpPathAvailable = copy($srcPath, $tmpPath)) {
            $imgUpService = new ImageUploadTestService();
            $imgUpValidator = new ImageUploadValidator($fileUpInfoArr);
            $oldNumberOfViews = ImageUploadTestService::getAndSetTotalNumberOfViews(TRUE);
            $postMgr = EntityManagerFactory::getPostManager();
            $oldNumberOfPosts = $postMgr->getNumberOfPosts();
            try {
                $imgUpValidator->checkIfFileIsCorrupted();
                $imgUpValidator->checkFileErrorValue();
                $imgUpValidator->checkFileMimeType();
                $imgUpValidator->checkFileSize();
                $imgUpValidator->checkFileWeight();
                $imgUpService->moveFile($tmpPath, $imgUpValidator->getMimeType());
            } catch (RuntimeException $ex) {
                $this->assertEquals($exPropertyValuesArr['code'], $ex->getCode());
                $this->assertEquals($exPropertyValuesArr['message'], $ex->getMessage());
                unlink($tmpPath);
            }
            $updatedNumberOfViews = ImageUploadTestService::getAndSetTotalNumberOfViews(TRUE);
            $updatedNumberOfPosts = $postMgr->getNumberOfPosts();
            $this->assertEquals($oldNumberOfViews, $updatedNumberOfViews);
            $this->assertEquals($oldNumberOfPosts, $updatedNumberOfPosts);
        } else {
            $this->assertTrue($tmpPathAvailable, $srcPath . ' could not be copied to ' . $tmpPath);
        }
    }

    /**
     * Test the successful creation of brand new posts
     *
     * @param array $imgUpInfoArr
     * @dataProvider ImageThreadTests\data_providers\PostMakerControllerDataProvider::makePostSuccessfulProvider
     */
    public function testMakePostSuccessful(array $imgUpInfoArr)
    {
        $imgFileName = $imgUpInfoArr['name'];
        $srcPath = __DIR__ . '/../localdir/' . $imgFileName;
        $tmpPath = $imgUpInfoArr['tmp_name'];
        if ($tmpPathAvailable = copy($srcPath, $tmpPath)) {
            $oldNumberOfViews = ImageUploadTestService::getAndSetTotalNumberOfViews(TRUE);
            $postMgr = EntityManagerFactory::getPostManager();
            $oldNumberOfPosts = $postMgr->getNumberOfPosts();
            $postMakerController = new PostMakerController();
            $postId = $postMakerController->makePost(array_pop($imgUpInfoArr), $imgUpInfoArr, new ImageUploadTestService());
            $updatedNumberOfViews = ImageUploadTestService::getAndSetTotalNumberOfViews(TRUE);
            $updatedNumberOfPosts = $postMgr->getNumberOfPosts();
            $brandNewPost = $postMgr->getPost($postId);
            $imgFileName = ImageUploadTestService::getImageName($srcPath);
            $imgFileExt = array_search($imgUpInfoArr['type'], ImageUploadValidator::getAllowedMimeTypes());
            $imgFileNameWithExt = sprintf('%s.%s', $imgFileName, $imgFileExt);
            $this->assertTrue(file_exists(getenv('REL_PATH_TO_POST_IMG') . '/' . $imgFileNameWithExt));
            $this->assertEquals($oldNumberOfViews, $updatedNumberOfViews);
            $this->assertEquals(++ $oldNumberOfPosts, $updatedNumberOfPosts);
            $this->assertTrue(is_object($brandNewPost));
            $removePostStatus = $postMgr->removePosts([
                $postId
            ]);
            if ($removePostStatus) {
                $unlinkPostStatus = unlink(getenv('REL_PATH_TO_POST_IMG') . '/' . $imgFileNameWithExt);
                if (! $unlinkPostStatus) {
                    $post = new Post($brandNewPost->imageTitle, $brandNewPost->imageFileName, $brandNewPost->timestamp);
                    $postMgr->createPost($post);
                }
            }
        } else {
            $this->assertTrue($tmpPathAvailable, $srcPath . ' could not be copied to ' . $tmpPath);
        }
    }
}
