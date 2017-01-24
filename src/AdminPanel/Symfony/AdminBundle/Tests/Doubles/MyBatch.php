<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericBatchElement;

class MyBatch extends GenericBatchElement
{
    public function getId()
    {
    }

    public function getDataIndexer()
    {
    }

    public function apply($object)
    {
    }
}
