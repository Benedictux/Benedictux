<?php
// src/AppBundle/Form/Type/PostEditType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PostEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('authorEmail');
    }

    public function getName()
    {
        return 'postEdit';
    }

    public function getParent()
    {
        return new PostType();
    }
}