<?php
/**
 * Bootstrapping script of the app tests. Tells PHPUnit which preparation steps to take before testing
 * 
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
/**
 * Loads composer default autoloader
 */
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../config/app_paths.php';
require_once __DIR__ . '/../config/ddbb_credentials.php';
require_once __DIR__ . '/../config/app_errors.php';