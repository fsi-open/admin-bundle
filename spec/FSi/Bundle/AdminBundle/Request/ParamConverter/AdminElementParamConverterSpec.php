<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Request\ParamConverter;

use FSi\Bundle\AdminBundle\Admin\Manager;
use PhpSpec\ObjectBehavior;

class AdminElementParamConverterSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Manager $manager
     */
    function let($manager)
    {
        $this->beConstructedWith($manager);
    }

    /**
     * @param \Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $configuration
     */
    function it_handle_only_fully_qualified_class_names($configuration)
    {
        $configuration->getClass()->willReturn('FSiDemoBundle:News');
        $this->supports($configuration)->shouldReturn(false);
    }

    /**
     * @param \Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $configuration
     */
    function it_supports_any_object_that_implements_element_interface($configuration)
    {
        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\CRUD\FormElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\CRUD\ListElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\Manager');
        $this->supports($configuration)->shouldReturn(false);
    }
}
