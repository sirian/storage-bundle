<?php

namespace Sirian\StorageBundle\Thumb;

use Intervention\Image\Image;
use Symfony\Component\DependencyInjection\Container;

abstract class AbstractFilter
{
    abstract public function handle(Image $image);

    public function getName()
    {
        $reflection = new \ReflectionObject($this);
        return Container::underscore(substr($reflection->getShortName(), 0, -6));
    }
}
