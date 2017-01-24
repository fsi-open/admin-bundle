<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Request\ParamConverter;

use AdminPanel\Symfony\AdminBundle\Admin\Manager;
use PhpSpec\ObjectBehavior;

class AdminElementParamConverterSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Manager $manager
     */
    public function let($manager)
    {
        $this->beConstructedWith($manager);
    }

    /**
     * @param \Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $configuration
     */
    public function it_handle_only_fully_qualified_class_names($configuration)
    {
        $configuration->getClass()->willReturn('FSiDemoBundle:News');
        $this->supports($configuration)->shouldReturn(false);
    }

    /**
     * @param \Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter $configuration
     */
    public function it_supports_any_object_that_implements_element_interface($configuration)
    {
        $configuration->getClass()->willReturn('AdminPanel\Symfony\AdminBundle\Doctrine\Admin\CRUDElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('AdminPanel\Symfony\AdminBundle\Admin\CRUD\AbstractCRUD');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('AdminPanel\Symfony\AdminBundle\Admin\Manager');
        $this->supports($configuration)->shouldReturn(false);
    }
}
