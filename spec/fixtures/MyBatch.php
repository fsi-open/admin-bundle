<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericBatchElement;
use FSi\Component\DataIndexer\DataIndexerInterface;

class MyBatch extends GenericBatchElement
{
    public function getId(): string
    {
    }

    public function getDataIndexer(): DataIndexerInterface
    {
    }

    public function apply($object): void
    {
    }
}
