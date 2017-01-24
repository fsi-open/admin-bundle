<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Menu\Builder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MenuBuilderSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function let($dispatcher)
    {
        $this->beConstructedWith($dispatcher, 'fsi_admin.menu.tools');
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     */
    public function it_should_emit_proper_event($dispatcher)
    {
        $dispatcher->dispatch('fsi_admin.menu.tools', Argument::allOf(
            Argument::type('AdminPanel\Symfony\AdminBundle\Event\MenuEvent')
        ))->shouldBeCalled();

        $this->buildMenu()->shouldReturnAnInstanceOf('AdminPanel\Symfony\AdminBundle\Menu\Item\Item');
    }
}
