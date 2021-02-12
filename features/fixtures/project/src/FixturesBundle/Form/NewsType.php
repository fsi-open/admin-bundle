<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\ImageType;
use FSi\FixturesBundle\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class, ['label' => 'admin.news.list.title']);

        $builder->add('date', DateType::class, [
            'label' => 'admin.news.list.date',
            'widget' => 'single_text',
            'required' => false,
        ]);

        $builder->add('created_at', DateType::class, [
            'label' => 'admin.news.list.created_at',
            'widget' => 'single_text'
        ]);

        $builder->add('visible', CheckboxType::class, [
            'label' => 'admin.news.list.visible',
            'required' => false,
        ]);

        $builder->add('creator_email', EmailType::class, [
            'label' => 'admin.news.list.creator_email'
        ]);

        $builder->add('photo', ImageType::class, [
            'label' => 'admin.news.list.photo',
            'required' => false
        ]);

        $builder->add('tags', CollectionType::class, [
            'entry_type' => TagType::class,
            'label' => 'admin.news.list.tags',
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ]);

        $builder->add('nonEditableTags', CollectionType::class, [
            'entry_type' => TextType::class,
            'data' => new ArrayCollection(['Tag 1', 'Tag 2', 'Tag 3']),
            'label' => 'admin.news.list.non_editable_tags',
            'allow_add' => false,
            'allow_delete' => false,
            'mapped' => false,
            'required' => false
        ]);

        $builder->add('removableComments', CollectionType::class, [
            'entry_type' => TextType::class,
            'data' => new ArrayCollection(['Comment 1', 'Comment 2', 'Comment 3']),
            'label' => 'admin.news.list.removable_comments',
            'allow_add' => false,
            'allow_delete' => true,
            'mapped' => false,
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', News::class);
    }
}
