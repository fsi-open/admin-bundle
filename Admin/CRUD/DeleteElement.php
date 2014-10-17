<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

interface DeleteElement extends BatchElement
{
    /**
     * @param mixed $object
     */
    public function delete($object);
}
