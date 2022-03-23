<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CollectionTypeExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [CollectionType::class];
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr']['data-allow-add'] = (int) $options['allow_add'];
        $view->vars['attr']['data-allow-delete'] = (int) $options['allow_delete'];
        $view->vars['attr']['data-prototype-name'] = $options['prototype_name'];
    }
}
