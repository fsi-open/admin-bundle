<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

abstract class GenericDeleteElement extends GenericBatchElement implements DeleteElement
{
    /**
     * @inheritdoc
     */
    public function apply($object)
    {
        $this->delete($object);
    }
}
