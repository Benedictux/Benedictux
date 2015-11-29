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
            ->add('title', 'text', array(
                'attr' => array (
                    'class' => 'select'
                    )))
            ->add('content', 'textarea', array(
                'attr' => array(
                    'id' => 'textarea',
                    'class' => 'textarea',
                    'cols' => 100,
                    'rows' => 30
                    )))
            ->add('published', 'checkbox', array(
                'required' => false,
                ))
        ;
    }

    public function getName()
    {
        return 'post';
    }
}