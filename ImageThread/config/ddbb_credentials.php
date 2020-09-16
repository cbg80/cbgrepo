<?php
/**
 * Declares all database connection parameters as constants
 * 
 * chown cbg:users /home/cbg/eclipse-workspace/ImageThread/webapp_model/ddbb_credentials.php
 * chmod 640 /home/cbg/eclipse-workspace/ImageThread/webapp_model/ddbb_credentials.php
 * @author Carlos Blanco Gañán <carlos_blanga@yahoo.es>
 * @package config
 */
/**
 * host name of mariadb server
 */
if (!defined('SERVER'))
    define ( 'SERVER', getenv ( 'MARIADB_PORT_3306_TCP_ADDR' ) );
/**
 * Name of the application database
 */
if (!defined('DATABASE'))
    define ( 'DATABASE', getenv('DATABASE') );
/**
 * Username of the mariadb server
 */
if (!defined('USER'))
    define ( 'USER', 'root' );
/**
 * Password of the user of the mariadb server
 */
if (!defined('PASSWORD'))
    define ( 'PASSWORD', getenv ( 'MARIADB_ENV_MYSQL_ROOT_PASSWORD' ) );
/**
 * Charset required for any mysql query result
 */
if (!defined('CHARSET'))
    define ( 'CHARSET', 'utf8' );