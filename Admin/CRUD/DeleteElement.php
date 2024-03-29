<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

/**
 * @template T of array<string,mixed>|object
 * @template-extends BatchElement<T>
 */
interface DeleteElement extends BatchElement
{
    /**
     * @param T $data
     */
    public function delete($data): void;
}
