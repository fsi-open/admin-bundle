<?php

namespace FSi\FixturesBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use FSi\Bundle\AdminBundle\Form\TypeSolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\DateType', 'date');
        $checkboxType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\CheckboxType', 'checkbox');
        $collectionEntryTypeOption = TypeSolver::hasCollectionEntryTypeOption() ? 'entry_type' : 'type';
        $collectionType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\CollectionType', 'collection');
        $imageType = TypeSolver::getFormType('FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\ImageType', 'fsi_image');
        $emailType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\EmailType', 'email');
        $tagType = TypeSolver::getFormType('FSi\FixturesBundle\Form\TagType', new TagType());
        $textType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextType', 'text');

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
    }

    public function getName()
    {
        return 'news_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'FSi\FixturesBundle\Entity\News'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'FSi\FixturesBundle\Entity\News'
        ]);
    }
}
