<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Tests\Fixtures;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

/**
 * It's dumb implementation of ManagerRegistry, but it's enough for testing purposes.
 */
class TestManagerRegistry implements ManagerRegistry
{
    /**
     * Test managers name.
     */
    const NAME = 'test';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultManagerName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultConnectionName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection($name = null)
    {
        return $this->em;
    }

    /**
     * {@inheritdoc}
     */
    public function getConnections()
    {
        return [$this->em];
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectionNames()
    {
        return [self::NAME];
    }


    /**
     * {@inheritdoc}
     */
    public function getManager($name = null)
    {
        return $this->em;
    }

    /**
     * {@inheritdoc}
     */
    public function getManagers()
    {
        return [$this->em];
    }

    /**
     * {@inheritdoc}
     */
    public function resetManager($name = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getAliasNamespace($alias)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerNames()
    {
        return [self::NAME];
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository($persistentObject, $persistentManagerName = null)
    {
        return $this->em;
    }

    /**
     * {@inheritdoc}
     */
    public function getManagerForClass($class)
    {
        return $this->em;
    }
}
