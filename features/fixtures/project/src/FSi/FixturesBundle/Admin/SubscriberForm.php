<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\FormElement;
use FSi\Bundle\AdminBundle\Form\TypeSolver;
use Symfony\Component\Form\FormFactoryInterface;
use FSi\FixturesBundle\Entity;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SubscriberForm extends FormElement
{
    public function getClassName(): string
    {
        return Entity\Subscriber::class;
    }

    public function getId(): string
    {
        return 'subscriber_form';
    }

    public function getSuccessRoute(): string
    {
        return 'fsi_admin_list';
    }

    public function getSuccessRouteParameters(): array
    {
        return ['element' => 'subscriber'];
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        $builder = $factory->createNamedBuilder(
            'subscriber',
            TypeSolver::getFormType(FormType::class, 'form'),
            $data,
            ['data_class' => $this->getClassName()]
        );

        $builder->add(
            'email',
            TypeSolver::getFormType(EmailType::class, 'email'),
            ['label' => 'admin.subscriber.list.email']
        );

        $builder->add(
            'created_at',
            TypeSolver::getFormType(DateType::class, 'date'),
            [
                'label' => 'admin.subscriber.list.created_at',
                'widget' => 'single_text'
            ]
        );

        $builder->add(
            'active',
            TypeSolver::getFormType(CheckboxType::class, 'checkbox'),
            ['label' => 'admin.subscriber.list.active']
        );

        return $builder->getForm();
    }
}
