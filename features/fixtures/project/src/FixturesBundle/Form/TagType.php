<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Form;

use FSi\FixturesBundle\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

class TagType extends AbstractType
{
    /**
     * @param FormBuilderInterface<FormBuilderInterface> $builder
     * @param array<string,mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, ['constraints' => [new NotBlank()]]);

        $builder->add('elements', CollectionType::class, [
            'entry_type' => TagElementType::class,
            'label' => 'admin.news.list.tag_elements',
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'prototype_name' => 'tagname',
            'constraints' => [new Valid()]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Tag::class);
    }
}
