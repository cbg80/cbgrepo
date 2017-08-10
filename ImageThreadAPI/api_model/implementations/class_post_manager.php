<?php
/**
 * Declares APIPostManagerImpl class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
/**
 * Imports the post entity class
 */
require_once __DIR__ . '/../../webapp_model/entities/class_post.php';
/**
 * Imports the interface implemented by the declared class
 */
require_once __DIR__ . '/../interfaces/post_manager.php';
/**
 * Imports the class that deals with DDBB connections
 */
require_once __DIR__ . '/../../webapp_model/class_ddbb.php';
/**
 * Encapsulates methods to manage any post
 *
 * @package api_model
 * @subpackage implementations
 */
class APIPostManagerImpl implements APIPostManager
{
    /**
     * 
     * {@inheritDoc}
     * @see APIPostManager::updatePost()
     */
    public function updatePost(Post $post): bool
    {
        $ret = FALSE;
        $sql = 'UPDATE `posts` SET `imageTitle` = ? WHERE `id` = ?';
        $dataBase = new DataBase();
        $conn = $dataBase->getConn();
        try {
            $statement = $conn->prepare($sql);
            $ret = $statement->execute(array($post->getImageTitle(), $post->getId()));
        } catch (PDOException $ex) {
            print 'ERROR ' . $ex->getMessage() . '<br/>';
        } finally {
            $dataBase->closeConn();
        }
        return  $ret;
    }
    /**
     * 
     * {@inheritDoc}
     * @see APIPostManager::getPostByImg()
     */
    public function getPostByImg(string $nameOfPostImg, string $titleOfPost): Post
    {
        $post = NULL;
        $sql = 'SELECT * FROM `posts` WHERE `imageFileName` = ?';
        $dataBase = new DataBase();
        $conn = $dataBase->getConn();
        try {
            $statement = $conn->prepare($sql);
            $statement->bindParam(1, $nameOfPostImg, PDO::PARAM_STR);
            $statement->execute();
            $post = $statement->fetchObject();
            $post = new Post($titleOfPost, $post->imageFileName, $post->timestamp, $post->id);
        } catch (PDOException $ex) {
            print 'ERROR ' . $ex->getMessage() . '<br/>';
        } finally {
            $dataBase->closeConn();
        }
        return $post;
    }
}