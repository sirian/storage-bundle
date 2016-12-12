<?php

namespace Sirian\StorageBundle\Twig;

use Sirian\StorageBundle\Storage\FileHelper;

class StorageExtension extends \Twig_Extension
{
    /**
     * @var FileHelper
     */
    private $helper;

    public function __construct(FileHelper $helper)
    {

        $this->helper = $helper;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('file_asset', [$this, 'fileAsset']),
            new \Twig_SimpleFunction('file_thumb', [$this, 'fileThumb']),
        ];
    }


    public function fileAsset($file)
    {
        return $this->helper->resolveUrl($file);
    }

    public function fileThumb($file, $filter)
    {
        return $this->helper->resolveThumbUrl($file, $filter);
    }

    public function getName()
    {
        return 'storage';
    }
}
