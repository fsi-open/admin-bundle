<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

interface CRUDElement extends ListElement, FormElement
{
    /**
     * This method is called from CRUDController in delete action.
     * Mostly this method should delete entity from database but it can be overwritten to
     * set flag instead of executing hard delete.
     *
     * @param mixed $object
     */
    public function delete($object);
}
