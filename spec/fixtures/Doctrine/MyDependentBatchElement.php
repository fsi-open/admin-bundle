<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures\Doctrine;

use FSi\Bundle\AdminBundle\Doctrine\Admin\DependentBatchElement;

class MyDependentBatchElement extends DependentBatchElement
{
    public function getClassName(): string
    {
        return 'FSiDemoBundle:Entity';
    }

    public function getId(): string
    {
        return 'my_entity_batch';
    }

    public function getParentId(): string
    {
        return 'my_parent_entity';
    }

    public function apply($object): void
    {
    }
}
