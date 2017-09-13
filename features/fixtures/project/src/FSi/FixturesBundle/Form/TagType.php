<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Form;

use FSi\Bundle\AdminBundle\Form\TypeSolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use FSi\FixturesBundle\Entity\Tag;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name');
        $builder->add(
            'elements',
            TypeSolver::getFormType(CollectionType::class, 'collection'),
            [
                TypeSolver::hasCollectionEntryTypeOption() ? 'entry_type' : 'type' =>
                    TypeSolver::getFormType(TagElementType::class, new TagElementType()),
                'label' => 'admin.news.list.tag_elements',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype_name' => 'tagname'
            ]
        );
    }

    public function getName(): string
    {
        return 'tag_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
