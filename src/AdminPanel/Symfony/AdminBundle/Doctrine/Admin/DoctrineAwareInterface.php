<?php


namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @deprecated Deprecated since version 1.1, to be removed in 1.2. Use
 *             AdminPanel\Symfony\AdminBundle\Doctrine\Admin\Element instead.
 */
interface DoctrineAwareInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     * @return mixed
     */
    public function setManagerRegistry(ManagerRegistry $registry);
}
