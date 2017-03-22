<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\FormElement;
use FSi\Bundle\AdminBundle\Form\TypeSolver;
use Symfony\Component\Form\FormFactoryInterface;

class SubscriberForm extends FormElement
{
    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Subscriber';
    }

    public function getId()
    {
        return 'subscriber_form';
    }

    public function getName()
    {
        return 'admin.subscriber.name';
    }

    public function getSuccessRoute()
    {
        return 'fsi_admin_list';
    }

    public function getSuccessRouteParameters()
    {
        return ['element' => 'subscriber'];
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $builder = $factory->createNamedBuilder(
            'subscriber',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form'),
            $data,
            ['data_class' => $this->getClassName()]
        );

        $builder->add(
            'email',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\EmailType', 'email'),
            ['label' => 'admin.subscriber.list.email',]
        );

        $builder->add(
            'created_at',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\DateType', 'date'),
            [
                'label' => 'admin.subscriber.list.created_at',
                'widget' => 'single_text'
            ]
        );

        $builder->add(
            'active',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\CheckboxType', 'checkbox'),
            ['label' => 'admin.subscriber.list.active',]
        );

        return $builder->getForm();
    }
}
