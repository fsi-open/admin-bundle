<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Component\DataIndexer\DataIndexerInterface;

interface DataIndexerElement extends Element
{
    /**
     * This method should be used inside of admin elements to retrieve DataIndexerInterface.
     */
    public function getDataIndexer(): DataIndexerInterface;
}
