<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericCRUDElement;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormFactoryInterface;

class FormWorkerSpec extends ObjectBehavior
{
    public function let(FormFactoryInterface $formFactory): void
    {
        $this->beConstructedWith($formFactory);
    }

    public function it_mount_form_factory_to_elements_that_are_form_aware(
        GenericCRUDElement $element,
        FormFactoryInterface $formFactory
    ): void {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
