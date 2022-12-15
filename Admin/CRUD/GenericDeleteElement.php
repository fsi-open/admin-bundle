<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use phpDocumentor\Reflection\DocBlock\Tags\Generic;

/**
 * @template T of array<string,mixed>|object
 * @template-extends GenericBatchElement<T>
 * @template-implements DeleteElement<T>
 */
abstract class GenericDeleteElement extends GenericBatchElement implements DeleteElement
{
    public function apply($object): void
    {
        $this->delete($object);
    }
}
