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

class DTOFormElement extends FormElement
{
    public function getClassName()
    {
        return 'FSi\FixturesBundle\DTO\Model';
    }

    public function getId()
    {
        return 'dto_form';
    }

    public function save($object)
    {
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $builder = $factory->createNamedBuilder(
            'dto_form',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form'),
            $data,
            ['data_class' => $this->getClassName()]
        );

        $builder->add(
            'email',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\EmailType', 'email'),
            ['label' => 'admin.email']
        );

        return $builder->getForm();
    }
}
