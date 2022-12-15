<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Display;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;
use FSi\Bundle\AdminBundle\Display\Display;

/**
 * @template T of array<string,mixed>|object
 * @template-extends DataIndexerElement<T>
 */
interface Element extends DataIndexerElement
{
    /**
     * @param T $data
     * @return Display
     */
    public function createDisplay($data): Display;
}
