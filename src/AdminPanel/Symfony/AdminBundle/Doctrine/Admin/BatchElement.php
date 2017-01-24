<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericBatchElement;

abstract class BatchElement extends GenericBatchElement implements Element
{
    use DataIndexerElementImpl;
}
