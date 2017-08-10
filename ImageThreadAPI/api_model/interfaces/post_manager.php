<?php
/**
 * Declares APIPostManager interface
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
/**
 * Imports the Post entity
 */
require_once __DIR__ . '/../../webapp_model/entities/class_post.php';
/**
 * Declares methods to manage brand new posts via the API server
 *
 * @package api_model
 * @subpackage interfaces
 */
interface APIPostManager
{
    /**
     * Queries the post that is related to the image whose name is got as the first argument
     * @param string $nameOfPostImg Name of the image connected with the requested post
     * @param string $titleOfPost Brand new title of the requested post
     * @return Post The post related to that image. It has already got the brand new title set
     */
    public function getPostByImg(string $nameOfPostImg, string $titleOfPost): Post;
    /**
     * Updates the post got as the first argument
     * @param Post $post The post required to be updated
     * @return bool TRUE if update operation successful. FALSE otherwise
     */
    public function updatePost(Post $post): bool;
}