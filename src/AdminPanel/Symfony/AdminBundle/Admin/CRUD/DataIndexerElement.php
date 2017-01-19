<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Admin\Element;

interface DataIndexerElement extends Element
{
    /**
     * This method should be used inside of admin objects to retrieve DataIndexerInterface.
     *
     * @return \FSi\Component\DataIndexer\DataIndexerInterface
     */
    public function getDataIndexer();
}
