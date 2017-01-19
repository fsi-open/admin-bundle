<?php


namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\AbstractCRUD;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class CRUDElement extends AbstractCRUD implements Element
{
    use DataIndexerElementImpl;

    /**
     * {@inheritdoc}
     */
    public function save($object)
    {
        $this->getObjectManager()->persist($object);
        $this->getObjectManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function saveDataGrid()
    {
        $this->getObjectManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($object)
    {
        $this->getObjectManager()->remove($object);
        $this->getObjectManager()->flush();
    }
}
