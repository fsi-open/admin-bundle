<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Display;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;

interface Element extends DataIndexerElement
{
    /**
     * @param mixed $object
     * @return \FSi\Bundle\AdminBundle\Display\Display
     */
    public function createDisplay($object);
}
