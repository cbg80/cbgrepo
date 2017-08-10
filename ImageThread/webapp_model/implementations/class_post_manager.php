<?php
require_once __DIR__ . '/../entities/class_post.php';
require_once __DIR__ . '/../interfaces/post_manager.php';
require_once __DIR__ . '/../class_ddbb.php';
class PostManagerImpl implements PostManager {
	function createPost(Post $post) {
		$ret = FALSE;
		$sql = 'INSERT INTO `posts` (`imageTitle`, `imageFileName`, `timestamp`) VALUES (?,?,?)';
		$dataBase = new DataBase ();
		$conn = $dataBase->getConn ();
		try {
			$statement = $conn->prepare ( $sql );
			$ret = $statement->execute ( array (
					$post->getImageTitle (),
					$post->getImageFileName (),
					$post->getTimestamp () 
			) );
			if ($ret) {
				$ret = $conn->lastInsertId ();
			}
		} catch ( PDOException $ex ) {
			print 'ERROR: ' . $ex->getMessage () . '<br/>';
		} finally {
			$dataBase->closeConn ();
		}
		return $ret;
	}
	function getPosts() {
		$sql = 'SELECT * FROM `posts` ORDER BY `timestamp` DESC';
		$posts = array ();
		$dataBase = new DataBase ();
		$conn = $dataBase->getConn ();
		try {
			$statement = $conn->query ( $sql );
			while ( $post = $statement->fetchObject () ) {
				$posts [] = $post;
			}
		} catch ( Exception $ex ) {
			print 'ERROR: ' . $ex->getMessage () . '<br/>';
		} finally {
			$dataBase->closeConn ();
		}
		return $posts;
	}
	function getPost($postId) {
		$post = NULL;
		$sql = 'SELECT * FROM `posts` WHERE `id` = ?';
		$dataBase = new DataBase ();
		$conn = $dataBase->getConn ();
		try {
			$statement = $conn->prepare ( $sql );
			$statement->bindParam ( 1, $postId, PDO::PARAM_INT );
			$statement->execute ();
			$post = $statement->fetchObject ();
		} catch ( PDOException $ex ) {
			print 'ERROR: ' . $ex->getMessage () . '<br/>';
		} finally {
			$dataBase->closeConn ();
		}
		return $post;
	}
	function getNumberOfPosts() {
		$numPosts = NULL;
		$sql = 'SELECT COUNT(*) AS `numPosts` FROM `posts`';
		$dataBase = new DataBase ();
		$conn = $dataBase->getConn ();
		try {
			$statement = $conn->query ( $sql );
			$numPosts = $statement->fetchObject ()->numPosts;
		} catch ( PDOException $ex ) {
			print 'ERROR: ' . $ex->getMessage () . '<br/>';
		} finally {
			$dataBase->closeConn ();
		}
		return $numPosts;
	}
}
?>