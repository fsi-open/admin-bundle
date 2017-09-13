<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\CRUD\DependentBatchElement;
use FSi\Component\DataIndexer\DataIndexerInterface;

class MyDependentBatch extends DependentBatchElement
{
    public function getId(): string
    {
        return 'my_dependent_batch';
    }

    public function getParentId(): string
    {
    }

    public function getDataIndexer(): DataIndexerInterface
    {
    }

    public function apply($object): void
    {
    }
}
