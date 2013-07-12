<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Structure\Doctrine;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Structure\AdminElementInterface as BaseElementInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface AdminElementInterface extends BaseElementInterface
{
    /**
     * @param ManagerRegistry $registry
     * @return $this
     */
    public function setManagerRegistry(ManagerRegistry $registry);

    /**
     * This function should be used inside of admin objects to retrieve ObjectManager
     *
     * @return \Doctrine\Common\Persistence\ObjectManager
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function getObjectManager();

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository();

    /**
     * Return repository name bound to this admin object.
     * Repository will be used to create/updated/delete entities.
     *
     * @return string
     */
    public function getClassName();
}