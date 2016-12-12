<?php

namespace Sirian\StorageBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;

/**
 * @Mongo\Document(collection="thumbs")
 */
class Thumb
{
    /**
     * @Mongo\Id(strategy="NONE", type="string")
     */
    protected $id;

    /**
     * @Mongo\Field(type="string")
     */
    protected $filter;

    /**
     * @var FileEmbed
     * @Mongo\EmbedOne(targetDocument="FileEmbed")
     */
    protected $image;

    /**
     * @Mongo\Index(expireAfterSeconds="1")
     * @Mongo\Field(type="date")
     */
    protected $expireAt;

    public function __construct()
    {
        $this->expireAt = new \DateTime('+1month');
    }


    public static function generateId($filename, $filter)
    {
        return $filename . ':' . $filter;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}
