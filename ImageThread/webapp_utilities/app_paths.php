<?php
/**
 * Declares all parameterized paths of the app as constants
 * 
 * @package webapp_utilities
 */
/**
 * Relative path to the image folder of any posts
 */
define('REL_PATH_TO_POST_IMG', getenv('REL_PATH_TO_POST_IMG'));
/**
 * Absolute path to the image folder of any posts
 */
define('ABS_PATH_TO_POST_IMG', getenv('DR') . '/' . getenv('REMOTE_DIR') . '/' . getenv('LDRB') . '/' . getenv('REL_PATH_TO_POST_IMG') . '/');
/**
 * Absolute path to the file where the total number of views of the web page is recorded
 */
define('ABS_PATH_TO_VIEW_COUNTER_FILE', getenv('DR') . '/' . getenv('REMOTE_DIR') . '/' . getenv('LDRB') . '/' . getenv('REL_PATH_TO_VIEW_COUNTER_FILE'));