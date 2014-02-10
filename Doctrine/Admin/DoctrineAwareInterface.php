<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface DoctrineAwareInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     * @return mixed
     */
    public function setManagerRegistry(ManagerRegistry $registry);
}