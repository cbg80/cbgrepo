<?php
namespace ImageThread\webapp_model\interfaces;
use ImageThread\webapp_model\entities\Post;

interface PostManager {
	function createPost(Post $post);
	function getPosts();
	function getPost($postId);
	function getNumberOfPosts();
	/**
	 * Removes the posts referenced by ID from the ddbb
	 * @param array $postIdArr
	 * @return bool
	 */
	public function removePosts(array $postIdArr): bool;
}
?>