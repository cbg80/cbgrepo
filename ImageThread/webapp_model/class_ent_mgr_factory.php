<?php
require_once __DIR__ . '/implementations/class_post_manager.php';
class EntityManagerFactory {
	static function getPostManager() {
		return new PostManagerImpl ();
	}
}
?>