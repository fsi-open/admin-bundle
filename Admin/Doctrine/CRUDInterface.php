<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface CRUDInterface
{
    /**
     * Class name that represent entity. It might be returned in Symfony2 style:
     * FSiDemoBundle:News
     * or as fully class name
     * \FSi\Bundle\DemoBundle\Entity\News
     *
     * @return string
     */
    public function getClassName();

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    public function getObjectManager();

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository();
}