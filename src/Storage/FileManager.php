<?php

namespace Sirian\StorageBundle\Storage;

use Doctrine\ODM\MongoDB\DocumentManager;
use Sirian\StorageBundle\Document\File;
use Sirian\StorageBundle\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileManager
{
    /**
     * @var DocumentManager
     */
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
        $this->repository = $dm->getRepository(File::class);
    }

    public function findFile($filename)
    {
        $file = $this->dm->getRepository(File::class)->findOneBy(['filename' => $filename]);
        if (!$file) {
            throw new FileNotFoundException();
        }
        return $file;
    }

    public function serve(File $file)
    {
        $response = new StreamedResponse(function () use ($file) {
            fpassthru($file->getResource());
        });

        $response->headers->set('Content-Type', $file->getContentType());

        $response->setMaxAge(30 * 86400);

        return $response;
    }
}
