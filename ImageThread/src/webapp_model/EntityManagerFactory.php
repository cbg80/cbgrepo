<?php
namespace ImageThread\webapp_model;

use ImageThread\webapp_model\implementations\PostManagerImpl;

class EntityManagerFactory {
	static function getPostManager() {
		return new PostManagerImpl ();
	}
}
?>