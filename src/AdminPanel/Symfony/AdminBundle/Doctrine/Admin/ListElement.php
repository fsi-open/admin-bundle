<?php


namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericListElement;

abstract class ListElement extends GenericListElement implements Element
{
    use DataIndexerElementImpl;

    /**
     * {@inheritdoc}
     */
    public function saveDataGrid()
    {
        $this->getObjectManager()->flush();
    }
}
