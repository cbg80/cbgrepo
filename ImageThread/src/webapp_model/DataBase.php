<?php
/**
 * Declares DataBase class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// @/etc/php/php.ini
// extension=pdo_mysql.so
namespace ImageThread\webapp_model;
/**
 * Encapsulates methods to connect and disconnect from the database server
 *
 * Relies on pdo driver.
 * 
 * @package webapp_model
 */
class DataBase {
	/**
	 * Connection link to the database server
	 * 
	 * @var \PDO
	 */
	private $connDataBase;
	/**
	 * Asigns to the only class attribute the connection link to the database server
	 *
	 * If any error occurs, prints out a message and disconnect from the database server
	 * 
	 * @uses webapp_model\ddbb_credentials.php
	 */
	function getConn() {
		$conn_str = 'mysql:host=' . SERVER . ';dbname=' . DATABASE . ';charset=' . CHARSET;
		try {
			$this->connDataBase = new \PDO ( $conn_str, USER, PASSWORD );
			$this->connDataBase->setAttribute ( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
			return $this->connDataBase;
		} catch ( \PDOException $ex ) {
			print 'ERROR: ' . $ex->getMessage () . '<br/>';
			$this->connDataBase = NULL;
		}
	}
	/**
	 * Disconnect from the database server
	 */
	function closeConn() {
		$this->connDataBase = NULL;
	}
}
?>