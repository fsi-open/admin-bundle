<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Menu\Builder;

use FSi\Bundle\AdminBundle\Event\MenuToolsEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;

class MenuBuilderSpec extends ObjectBehavior
{
    public function let(EventDispatcherInterface $dispatcher): void
    {
        $this->beConstructedWith($dispatcher, MenuToolsEvent::class);
    }

    public function it_should_emit_proper_event(EventDispatcherInterface $dispatcher): void
    {
        $dispatcher->dispatch(Argument::type(MenuToolsEvent::class))->shouldBeCalled();

        $this->buildMenu()->shouldReturnAnInstanceOf(Item::class);
    }
}
