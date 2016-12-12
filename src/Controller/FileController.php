<?php

namespace Sirian\StorageBundle\Controller;

use Sirian\StorageBundle\Exception\FileNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileController extends Controller
{
    public function fileAction($name)
    {
        $fileManager = $this->getFileManager();

        try {
            return $fileManager->serve($fileManager->findFile($name));
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException(null, $e);
        }
    }

    public function thumbAction($filter, $name)
    {
        $thumbManager = $this->container->get('sirian_storage.thumb_manager');

        try {
            $thumb = $thumbManager->getThumb($name, $filter);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException(null, $e);
        }

        $fileManager = $this->getFileManager();

        return $fileManager->serve($thumb->getImage()->getFile());
    }

    protected function getFileManager()
    {
        return $this->container->get('sirian_storage.file_manager');
    }
}
