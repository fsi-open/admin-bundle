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

/**
 * @template T of array<string,mixed>|object
 */
interface DataIndexerElement extends Element
{
    /**
     * @return DataIndexerInterface<T>
     */
    public function getDataIndexer(): DataIndexerInterface;
}
