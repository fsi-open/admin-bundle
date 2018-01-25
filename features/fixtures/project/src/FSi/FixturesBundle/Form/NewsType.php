<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use FSi\Bundle\AdminBundle\Form\TypeSolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\ImageType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FSi\FixturesBundle\Entity\News;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $dateType = TypeSolver::getFormType(DateType::class, 'date');
        $checkboxType = TypeSolver::getFormType(CheckboxType::class, 'checkbox');
        $collectionEntryTypeOption = TypeSolver::hasCollectionEntryTypeOption() ? 'entry_type' : 'type';
        $collectionType = TypeSolver::getFormType(CollectionType::class, 'collection');
        $imageType = TypeSolver::getFormType(ImageType::class, 'fsi_image');
        $emailType = TypeSolver::getFormType(EmailType::class, 'email');
        $tagType = TypeSolver::getFormType(TagType::class, new TagType());
        $textType = TypeSolver::getFormType(TextType::class, 'text');

        $builder->add('title', $textType, ['label' => 'admin.news.list.title']);

        $builder->add('date', $dateType, [
            'label' => 'admin.news.list.date',
            'widget' => 'single_text',
            'required' => false,
        ]);

        $builder->add('created_at', $dateType, [
            'label' => 'admin.news.list.created_at',
            'widget' => 'single_text'
        ]);

        $builder->add('visible', $checkboxType, [
            'label' => 'admin.news.list.visible',
            'required' => false,
        ]);

        $builder->add('creator_email', $emailType, [
            'label' => 'admin.news.list.creator_email'
        ]);

        $builder->add('photo', $imageType, [
            'label' => 'admin.news.list.photo',
            'required' => false
        ]);

        $builder->add('tags', $collectionType, [
            $collectionEntryTypeOption => $tagType,
            'label' => 'admin.news.list.tags',
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ]);

        $builder->add('nonEditableTags', $collectionType, [
            $collectionEntryTypeOption => $textType,
            'data' => new ArrayCollection(['Tag 1', 'Tag 2', 'Tag 3']),
            'label' => 'admin.news.list.non_editable_tags',
            'allow_add' => false,
            'allow_delete' => false,
            'mapped' => false,
            'required' => false
        ]);

        $builder->add('removableComments', $collectionType, [
            $collectionEntryTypeOption => $textType,
            'data' => new ArrayCollection(['Comment 1', 'Comment 2', 'Comment 3']),
            'label' => 'admin.news.list.removable_comments',
            'allow_add' => false,
            'allow_delete' => true,
            'mapped' => false,
            'required' => false
        ]);
    }

    public function getName(): string
    {
        return 'news_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver): void
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class
        ]);
    }
}
