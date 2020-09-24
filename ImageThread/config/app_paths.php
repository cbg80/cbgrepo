<?php
/**
 * Declares all parameterized paths of the app as constants
 * 
 * @package config
 */
/**
 * Relative path to the image folder of any posts
 */
if (!defined('REL_PATH_TO_POST_IMG'))
    define('REL_PATH_TO_POST_IMG', getenv('REL_PATH_TO_POST_IMG'));
/**
 * Absolute path to the image folder of any posts
 */
if (!defined('ABS_PATH_TO_POST_IMG'))
    define('ABS_PATH_TO_POST_IMG', getenv('DR') . '/' . getenv('REMOTE_DIR') . '/' . getenv('LDRB') . '/' . getenv('REL_PATH_TO_POST_IMG') . '/');
/**
 * Absolute path to the file where the total number of views of the web page is recorded
 */
if (!defined('ABS_PATH_TO_VIEW_COUNTER_FILE'))
    define('ABS_PATH_TO_VIEW_COUNTER_FILE', getenv('DR') . '/' . getenv('REMOTE_DIR') . '/' . getenv('LDRB') . '/' . getenv('REL_PATH_TO_VIEW_COUNTER_FILE'));