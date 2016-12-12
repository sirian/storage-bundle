<?php

namespace Sirian\StorageBundle\Storage;

use Sirian\StorageBundle\Document\File;
use Sirian\StorageBundle\Document\FileEmbed;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Router;

class FileHelper
{
    /**
     * @var Router
     */
    private $router;

    private $tmpFiles = [];

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function createFileEmbed($name, $content, $contentType = null)
    {
        $tmpFile = tmpfile();

        $this->tmpFiles[] = $tmpFile; //force tmpfile live until script end

        $meta = stream_get_meta_data($tmpFile);

        fwrite($tmpFile, $content);

        $uploaded = new UploadedFile($meta['uri'], $name, $contentType, null, null, true);

        $embed = new FileEmbed();

        $embed->setUploadedFile($uploaded);

        return $embed;
    }

    public function resolveThumbUrl($file, $filter)
    {
        if (!$file) {
            return '';
        }

        if ($file instanceof FileEmbed) {
            if (!$file->getFile()) {
                return '';
            }
            $filename = $file->getFilename();
        } elseif ($file instanceof File) {
            $filename = $file->getFilename();
        } else {
            throw new \InvalidArgumentException();
        }



        return $this->router->generate('sirian_storage_thumb', [
            'name' => $filename,
            'filter' => $filter
        ]);
    }

    public function resolveUrl($file)
    {
        if (!$file) {
            return '';
        }

        if ($file instanceof FileEmbed) {
            if (!$file->getFile()) {
                return '';
            }
            $filename = $file->getFilename();
        } elseif ($file instanceof File) {
            $filename = $file->getFilename();
        } else {
            throw new \InvalidArgumentException();
        }

        return $this->router->generate('sirian_storage_file', [
            'name' => $filename
        ]);
    }
}
