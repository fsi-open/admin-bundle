<?php

namespace FSi\FixturesBundle\Form;

use FSi\Bundle\AdminBundle\Form\TypeSolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add(
            'elements',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\CollectionType', 'collection'),
            array(
                TypeSolver::hasCollectionEntryTypeOption() ? 'entry_type' : 'type' =>
                    TypeSolver::getFormType('FSi\FixturesBundle\Form\TagElementType', new TagElementType()),
                'label' => 'admin.news.list.tag_elements',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype_name' => 'tagname'
            )
        );
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FSi\FixturesBundle\Entity\Tag',
        ));
    }
}
