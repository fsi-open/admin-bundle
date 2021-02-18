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
use FSi\FixturesBundle\DTO\Model;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class DTOFormElement extends FormElement
{
    public function getClassName(): string
    {
        return Model::class;
    }

    public function getId(): string
    {
        return 'dto_form';
    }

    public function save($data): void
    {
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        $builder = $factory->createNamedBuilder(
            'dto_form',
            FormType::class,
            $data,
            ['data_class' => $this->getClassName()]
        );

        $builder->add(
            'email',
            EmailType::class,
            ['label' => 'admin.email']
        );

        return $builder->getForm();
    }
}
