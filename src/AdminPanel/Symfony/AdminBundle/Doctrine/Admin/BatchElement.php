<?php


namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericBatchElement;

abstract class BatchElement extends GenericBatchElement implements Element
{
    use DataIndexerElementImpl;
}
