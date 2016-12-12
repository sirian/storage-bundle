<?php

namespace Sirian\StorageBundle\Document;

use Doctrine\MongoDB\GridFSFile;
use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

/**
 * @Mongo\Document(collection="fs")
 */
class File
{
    /**
     * @Mongo\Id
     */
    private $id;

    /**
     * @Mongo\Field(type="string")
     */
    private $filename;

    /**
     * @var GridFSFile
     * @Mongo\File
     */
    private $file;

    /**
     * @Mongo\Field(type="date")
     */
    private $uploadDate;

    /**
     * @Mongo\Field(type="string")
     */
    private $length;

    /**
     * @Mongo\Field(type="string")
     */
    private $chunkSize;

    /**
     * @Mongo\Field(type="string")
     */
    private $md5;

    /**
     * @Mongo\Field(type="string")
     */
    protected $contentType;

    public function getId()
    {
        return $this->id;
    }

    public function setFile(SymfonyFile $file)
    {
        $this->file = new GridFSFile($file->getRealPath());
        $this->contentType = $file->getMimeType();
        return $this;
    }

    public function getResource()
    {
        if (!$this->file) {
            return null;
        }

        if ($this->file->isDirty()) {
            if ($this->file->getFilename()) {
                return fopen($this->file->getFilename(), 'r+');
            } else {
                $stream = fopen('php://temp','r+');
                fwrite($stream, $this->file->getBytes());
                rewind($stream);
                return $stream;
            }
        } else {
            return $this->file->getMongoGridFSFile()->getResource();
        }
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function getMd5()
    {
        return $this->md5;
    }

    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    public function __clone()
    {
        if ($this->file) {
            $this->file = clone $this->file;
        }
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }
}
