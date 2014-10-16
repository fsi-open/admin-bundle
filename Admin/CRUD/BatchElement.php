<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\RedirectableElement;

interface BatchElement extends DataIndexerElement, RedirectableElement
{
    /**
     * This method is called from BatchController after action is confirmed.
     *
     * @param mixed $object
     */
    public function apply($object);
}
