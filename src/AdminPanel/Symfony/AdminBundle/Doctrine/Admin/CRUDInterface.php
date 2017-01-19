<?php


namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

/**
 * @deprecated Deprecated since version 1.1, to be removed in 1.2. Use
 *             AdminPanel\Symfony\AdminBundle\Doctrine\Admin\Element instead.
 */
interface CRUDInterface
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
}
