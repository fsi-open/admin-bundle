<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;

class MyResource extends GenericResourceElement
{
    public function getKey(): string
    {
        return 'resources.main_page';
    }

    public function getId(): string
    {
        return 'main_page';
    }

    public function getResourceValueRepository(): ResourceValueRepository
    {
    }

    public function save(ResourceValue $resource): void
    {
    }
}
