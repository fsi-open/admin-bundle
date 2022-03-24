<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\spec\fixtures\Admin\RequestStackAwareElement;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStackWorkerSpec extends ObjectBehavior
{
    public function let(RequestStack $requestStack): void
    {
        $this->beConstructedWith($requestStack);
    }

    public function it_mount_request_stack_to_elements_that_are_request_stack_aware(
        RequestStackAwareElement $element,
        RequestStack $requestStack
    ): void {
        $element->setRequestStack($requestStack)->shouldBeCalled();

        $this->mount($element);
    }
}
