<?php

namespace FSi\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class CollectionTypeExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['allow_add'] = (int) $options['allow_add'];
        $view->vars['allow_delete'] = (int) $options['allow_delete'];
        $view->vars['prototype_name'] = $options['prototype_name'];
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return 'collection';
    }
}
