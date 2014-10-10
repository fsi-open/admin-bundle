<?php

namespace FSi\FixturesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('elements', 'collection', array(
            'type' => new TagElementType(),
            'label' => 'admin.news.list.tag_elements',
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'prototype_name' => 'tagname'
        ));
    }

    public function getName()
    {
        return 'tag_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FSi\FixturesBundle\Entity\Tag',
        ));
    }
}
