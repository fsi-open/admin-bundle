<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures\Admin;

use Doctrine\Persistence\ManagerRegistry;

class DoctrineElement extends SimpleAdminElement
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function setManagerRegistry(ManagerRegistry $registry): void
    {
        $this->registry = $registry;
    }

    public function isDoctrineAware(): bool
    {
        return isset($this->registry);
    }
}
