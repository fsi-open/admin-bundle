<?php


namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;

trait ElementImpl
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $registry;

    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     */
    public function setManagerRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RuntimeException
     */
    public function getObjectManager()
    {
        $om = $this->registry->getManagerForClass($this->getClassName());

        if (is_null($om)) {
            throw new RuntimeException(sprintf('Registry manager does\'t have manager for class "%s".', $this->getClassName()));
        }

        return $om;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository()
    {
        return $this->getObjectManager()->getRepository($this->getClassName());
    }
}
