<?php
// src/AppBundle/Form/Type/PostType.php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('content', 'textarea')
            ->add('authorEmail','email')
            ->add('published', 'checkbox')
        ;
    }

    public function getName()
    {
        return 'post';
    }
}