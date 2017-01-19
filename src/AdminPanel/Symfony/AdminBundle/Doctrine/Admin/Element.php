<?php


namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface Element extends CRUDInterface, DoctrineAwareInterface
{
    /**
     * Class name that represent entity. It might be returned in Symfony2 style:
     * FSiDemoBundle:News
     * or as a full class name
     * \FSi\Bundle\DemoBundle\Entity\News
     *
     * @return string
     */
    public function getClassName();

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RuntimeException
     */
    public function getObjectManager();

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository();

    /**
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry
     * @return null
     */
    public function setManagerRegistry(ManagerRegistry $registry);
}
