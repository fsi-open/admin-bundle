<?php


namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles;

use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ResourceElement;

class MyResourceElement extends ResourceElement
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'main_page';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin.main_page';
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'resources.main_page';
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSi\Bundle\DemoBundle\Entity\Resource';
    }
}