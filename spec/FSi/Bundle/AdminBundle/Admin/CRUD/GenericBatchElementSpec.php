<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use PhpSpec\ObjectBehavior;
use FSi\Bundle\AdminBundle\spec\fixtures\MyBatch;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericBatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\Element;

class GenericBatchElementSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beAnInstanceOf(MyBatch::class);
        $this->beConstructedWith([]);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(GenericBatchElement::class);
    }

    public function it_is_delete_element(): void
    {
        $this->shouldHaveType(BatchElement::class);
    }

    public function it_is_admin_element(): void
    {
        $this->shouldHaveType(Element::class);
    }

    public function it_has_default_route(): void
    {
        $this->getRoute()->shouldReturn('fsi_admin_batch');
    }
}
