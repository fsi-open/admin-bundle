<?php


namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Doctrine;

use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\DeleteElement;

class MyDeleteElement extends DeleteElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSiDemoBundle:Entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'my_entity_batch';
    }
}
