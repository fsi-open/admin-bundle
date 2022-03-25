<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use Doctrine\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use PhpSpec\ObjectBehavior;

class DoctrineWorkerSpec extends ObjectBehavior
{
    public function let(ManagerRegistry $managerRegistry): void
    {
        $this->beConstructedWith($managerRegistry);
    }

    public function it_mount_datagrid_factory_to_elements_that_are_doctrine_elements(
        CRUDElement $element,
        ManagerRegistry $managerRegistry
    ): void {
        $element->setManagerRegistry($managerRegistry)->shouldBeCalled();

        $this->mount($element);
    }
}
