<?php
/**
 * Declares APIEntityManagerFactory class
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
/**
 * Imports APIPostManagerImpl class
 */
require_once __DIR__ . '/implementations/class_post_manager.php';
/**
 * Encapsulates a static method that returns an instance of APIPostManagerImpl class
 *
 * @package api_model
 */
class APIEntityManagerFactory
{
    /**
     * Returns an instance of APIPostManagerImpl class
     * @return APIPostManagerImpl
     */
    public static function getPostManager(): APIPostManagerImpl
    {
        return new APIPostManagerImpl();
    }
}