<?php

namespace FSi\FixturesBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', [
            'label' => 'admin.news.list.title',
        ]);
        $builder->add('date', 'date', [
            'label' => 'admin.news.list.date',
            'widget' => 'single_text',
            'required' => false,
        ]);
        $builder->add('created_at', 'date', [
            'label' => 'admin.news.list.created_at',
            'widget' => 'single_text'
        ]);
        $builder->add('visible', 'checkbox', [
            'label' => 'admin.news.list.visible',
            'required' => false,
        ]);
        $builder->add('creator_email', 'email', [
            'label' => 'admin.news.list.creator_email'
        ]);
        $builder->add('photo', 'fsi_image', [
            'label' => 'admin.news.list.photo',
            'required' => false
        ]);
        $builder->add('tags', 'collection', [
            'type' => new TagType(),
            'label' => 'admin.news.list.tags',
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ]);
        $builder->add('nonEditableTags', 'collection', [
            'type' => 'text',
            'data' => new ArrayCollection(['Tag 1', 'Tag 2', 'Tag 3']),
            'label' => 'admin.news.list.non_editable_tags',
            'allow_add' => false,
            'allow_delete' => false,
            'mapped' => false,
            'required' => false
        ]);
        $builder->add('removableComments', 'collection', [
            'type' => 'text',
            'data' => new ArrayCollection(['Comment 1', 'Comment 2', 'Comment 3']),
            'label' => 'admin.news.list.removable_comments',
            'allow_add' => false,
            'allow_delete' => true,
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
}
