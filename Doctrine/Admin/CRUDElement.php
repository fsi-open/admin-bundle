<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;

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
