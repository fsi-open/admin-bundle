<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\DoctrineAwareInterface;

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
