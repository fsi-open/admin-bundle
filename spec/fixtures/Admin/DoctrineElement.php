<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\spec\fixtures\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Doctrine\Admin\DoctrineAwareInterface;

class DoctrineElement extends SimpleAdminElement implements DoctrineAwareInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @param ManagerRegistry $registry
     * @return mixed
     */
    public function setManagerRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function isDoctrineAware()
    {
        return isset($this->registry);
    }
}
