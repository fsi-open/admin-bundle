<?php


namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Doctrine;

use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\BatchElement;

class MyBatchElement extends BatchElement
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

    public function apply($object)
    {
    }
}
