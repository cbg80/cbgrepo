<?php
/**
 * Declares Post class
 *
 * @author Carlos Blanco Gañán <carlos.blanga@gmail.com>
 */
// src/AppBundle/Entity/Post.php
namespace AppBundle\Entity;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Encapsulates methods to set and get any property of any post
 */
class Post
{
    private
            /**
             * Unique identifier of any post
             *
             * @var int
             */
            $id
            /**
             * Brief description of any post
             *
             * @var string
             */
            ,$title
            /**
             * Post image
             *
             * @var File|string
             */
            ,$image
            /**
             * Timestamp of any post
             *
             * @var \DateTime
             */
            ,$timestamp
    ;
    /**
     * @return int $id
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return File|string $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param File|string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return \DateTime $timestamp
     */
    public function getTimestamp() : \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
    }
}