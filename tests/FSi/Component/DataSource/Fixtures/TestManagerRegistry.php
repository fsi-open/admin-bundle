<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        return array($this->em);
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectionNames()
    {
        return array(self::NAME);
    }


    /**
     * {@inheritdoc}
     */
    function getManager($name = null)
    {
        return $this->em;
    }

    /**
     * {@inheritdoc}
     */
    function getManagers()
    {
        return array($this->em);
    }

    /**
     * {@inheritdoc}
     */
    function resetManager($name = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    function getAliasNamespace($alias)
    {
    }

    /**
     * {@inheritdoc}
     */
    function getManagerNames()
    {
        return array(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    function getRepository($persistentObject, $persistentManagerName = null)
    {
        return $this->em;
    }

    /**
     * {@inheritdoc}
     */
    function getManagerForClass($class)
    {
        return $this->em;
    }
}
