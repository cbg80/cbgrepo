<?php
require_once __DIR__ . '/../entities/class_post.php';
interface PostManager {
	function createPost(Post $post);
	function getPosts();
	function getPost($postId);
	function getNumberOfPosts();
}
?>