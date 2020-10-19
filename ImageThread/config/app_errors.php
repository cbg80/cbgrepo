<?php
/**
 * Parameterize the errors of the app
 *
 * @package config
 */
/**
 * Sets each app error as a constant associative array
 */
if (! defined('IMG_THREAD_UPLOAD_ERR_MOVED'))
    define('IMG_THREAD_UPLOAD_ERR_MOVED', [
        'code' => - 1,
        'message' => 'Uploaded image cannot be moved'
    ]);
if (! defined('IMG_THREAD_UPLOAD_ERR_FORBIDDEN'))
    define('IMG_THREAD_UPLOAD_ERR_FORBIDDEN', [
        'code' => - 2,
        'message' => 'Not allowed internet media type'
    ]);
if (! defined('IMG_THREAD_UPLOAD_ERR_HEAVIER'))
    define('IMG_THREAD_UPLOAD_ERR_HEAVIER', [
        'code' => - 3,
        'message' => 'Weight of uploaded image exceeds server side limit'
    ]);
if (! defined('IMG_THREAD_UPLOAD_ERR_HIGHER'))
    define('IMG_THREAD_UPLOAD_ERR_HIGHER', [
        'code' => - 4,
        'message' => 'Height of uploaded image exceeds server side limit'
    ]);
if (! defined('IMG_THREAD_UPLOAD_ERR_WIDER'))
    define('IMG_THREAD_UPLOAD_ERR_WIDER', [
        'code' => - 5,
        'message' => 'Width of uploaded image exceeds server side limit'
    ]);
if (! defined('IMG_THREAD_UPLOAD_ERR_UNKNOWN'))
    define('IMG_THREAD_UPLOAD_ERR_UNKNOWN', [
        'code' => - 6,
        'message' => 'Unknown upload error'
    ]);
if (! defined('IMG_THREAD_UPLOAD_ERR_CORRUPTED'))
    define('IMG_THREAD_UPLOAD_ERR_CORRUPTED', [
        'code' => - 7,
        'message' => 'Uploaded image corrupted'
    ]);