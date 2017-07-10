<?php

namespace Sirian\StorageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StorageFileCollectionType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entry_type' => StorageFileType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => 'Файлы',
            'prototype' => true,
            'entry_options' => [
                'label' => false
            ]
        ]);
    }

    public function getBlockPrefix()
    {
        return 'storage_files';
    }
}
