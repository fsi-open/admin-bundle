<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Fixtures;

use Doctrine\ORM\EntityManager;
use FSi\Component\DataGrid\Tests\Fixtures\EntityRepositoryMock;

class EntityManagerMock extends EntityManager
{
    protected $eventManager;

    protected $metadataFactory;

    public function __construct()
    {
    }

    public function _setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function _setMetadataFactory($metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function getMetadataFactory()
    {
        return $this->metadataFactory;
    }

    public function getEventManager()
    {
        return $this->eventManager;
    }

    public function getClassMetadata($className)
    {
        return null;
    }

    public function getRepository($entityName)
    {
        return new EntityRepositoryMock();
    }
}
