<?php

namespace Sirian\StorageBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManagerStatic;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Mongo\EmbeddedDocument
 */
class FileEmbed
{
    /**
     * @var File
     * @Mongo\ReferenceOne(targetDocument="File", cascade={"persist", "remove"}, simple=true, orphanRemoval=true)
     */
    protected $file;

    /**
     * @Mongo\Field(type="string")
     */
    protected $name;

    /**
     * @Mongo\Field(type="string")
     */
    protected $filename;

    /**
     * @Mongo\Field(type="string")
     */
    protected $contentType;

    /**
     * @Mongo\Field(type="int")
     */
    protected $size;

    /**
     * @Mongo\Field(type="date")
     * @var \DateTime
     */
    protected $date;

    /**
     * @Mongo\Field(type="boolean")
     */
    protected $isImage;

    /**
     * @Mongo\Field(type="int")
     */
    protected $width;

    /**
     * @Mongo\Field(type="int")
     */
    protected $height;

    /**
     * @var SymfonyFile
     */
    protected $uploadedFile;

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function isImage()
    {
        return $this->isImage;
    }

    public function setIsImage($isImage)
    {
        $this->isImage = $isImage;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    public function setUploadedFile(SymfonyFile $file)
    {
        $this->uploadedFile = $file;
        $this->file = null;
        $this->date = new \DateTime();


        if (!$file->isFile()) {
            return;
        }

        $this->file = new File();

        $this->contentType = $file->getMimeType();


        if ($file instanceof UploadedFile) {
            $this->name = $file->getClientOriginalName();
        } else {
            $this->name = basename($file->getRealPath());
        }

        $this->file->setFile($file);

        $this->updateFilename(pathinfo($this->name, PATHINFO_EXTENSION));

        $this->size = $file->getSize();
        $this->date = new \DateTime();
        $this->isImage = false;
        $this->width = $this->height = null;

        try {
            $image = ImageManagerStatic::make($file->getRealPath());
            $this->isImage = $image->width() && $image->height();
            if ($this->isImage) {
                $this->width = $image->width();
                $this->height = $image->height();
            }
        } catch (NotReadableException $e) {

        }
    }

    public function getFile()
    {
        return $this->file;
    }

    public function __clone()
    {
        if ($this->file) {
            $this->file = clone $this->file;
        }

        $this->updateFilename();
    }

    protected function updateFilename($ext = null)
    {
        if (!$ext && $this->filename) {
            $ext = pathinfo($this->filename, PATHINFO_EXTENSION);
        }
        $this->filename = base_convert(sha1(uniqid(microtime(true), true)), 16, 36) . ($ext ? '.' . $ext : '');

        if ($this->file) {
            $this->file->setFilename($this->filename);
        }
    }

    public function getFilename()
    {
        return $this->filename;
    }
}
