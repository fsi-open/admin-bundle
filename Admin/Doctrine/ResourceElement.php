<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class ResourceElement extends AbstractResource implements DoctrineAwareInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $registry;

    protected $om;

    /**
     * {@inheritdoc}
     */
    public function setManagerRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return string
     */
    abstract public function getClassName();

    public function getRepository()
    {
        return $this->registry->getRepository($this->getClassName());
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectManager()
    {
        if (!isset($this->om)) {
            $this->om = $this->registry->getManagerForClass($this->getClassName());
        }

        if (is_null($this->om)) {
            throw new RuntimeException(sprintf('Registry manager does\'t have manager for class "%s".', $this->getClassName()));
        }

        return $this->om;
    }
}