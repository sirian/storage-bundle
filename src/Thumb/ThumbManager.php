<?php

namespace Sirian\StorageBundle\Thumb;

use Doctrine\ODM\MongoDB\DocumentManager;
use Intervention\Image\ImageManagerStatic;
use Sirian\StorageBundle\Document\File;
use Sirian\StorageBundle\Document\Thumb;
use Sirian\StorageBundle\Storage\FileHelper;
use Sirian\StorageBundle\Storage\FileManager;

class ThumbManager
{
    protected $filters = [];

    protected $dm;

    /**
     * @var FileHelper
     */
    private $fileHelper;

    /**
     * @var FileManager
     */
    private $fileManager;

    public function __construct(DocumentManager $dm, FileManager $fileManager, FileHelper $fileHelper)
    {
        $this->dm = $dm;
        $this->fileHelper = $fileHelper;
        $this->fileManager = $fileManager;
    }

    public function addFilter(AbstractFilter $filter)
    {
        $this->filters[$filter->getName()] = $filter;
    }

    /**
     * @param $filename
     * @param $filterName
     * @return Thumb
     */
    public function getThumb($filename, $filterName)
    {

        $id = Thumb::generateId($filename, $filterName);

        $thumb = $this->dm->getRepository(Thumb::class)->find($id);

        if (!$thumb) {
            $file = $this->fileManager->findFile($filename);

            if (!isset($this->filters[$filterName])) {
                throw new \InvalidArgumentException();
            }

            $filter = $this->filters[$filterName];

            $thumb = $this->makeThumb($id, $file, $filter);
        }

        return $thumb;
    }

    private function makeThumb($id, File $file, AbstractFilter $filter)
    {
        $thumb = new Thumb();

        $image = ImageManagerStatic::make($file->getResource());
        $filter->handle($image);

        $embed = $this->fileHelper->createFileEmbed($file->getFilename(), $image->encode(), $image->mime());

        $thumb
            ->setId($id)
            ->setFilter($filter->getName())
            ->setImage($embed)
        ;

        $this->dm->persist($thumb);
        $this->dm->flush();

        return $thumb;
    }
}
