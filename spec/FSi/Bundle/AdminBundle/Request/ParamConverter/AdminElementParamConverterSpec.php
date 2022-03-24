<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Request\ParamConverter;

use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use PhpSpec\ObjectBehavior;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericCRUDElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\Manager;

class AdminElementParamConverterSpec extends ObjectBehavior
{
    public function let(ManagerInterface $manager): void
    {
        $this->beConstructedWith($manager);
    }

    public function it_handle_only_fully_qualified_class_names(ParamConverter $configuration): void
    {
        $configuration->getClass()->willReturn('FSiDemoBundle:News');
        $this->supports($configuration)->shouldReturn(false);
    }

    public function it_does_not_support_classless_param_converter(ParamConverter $configuration): void
    {
        $configuration->getClass()->willReturn(null);
        $this->supports($configuration)->shouldReturn(false);
    }

    public function it_supports_any_object_that_implements_element_interface(ParamConverter $configuration): void
    {
        $configuration->getClass()->willReturn(CRUDElement::class);
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn(GenericCRUDElement::class);
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn(FormElement::class);
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn(ListElement::class);
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn(BatchElement::class);
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn(Manager::class);
        $this->supports($configuration)->shouldReturn(false);
    }
}
