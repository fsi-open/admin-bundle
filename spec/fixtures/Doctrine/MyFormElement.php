<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures\Doctrine;

use FSi\Bundle\AdminBundle\Doctrine\Admin\FormElement;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class MyFormElement extends FormElement
{
    public function getClassName(): string
    {
        return 'FSiDemoBundle:Entity';
    }

    public function getId(): string
    {
        return 'my_entity_form';
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
    }
}
