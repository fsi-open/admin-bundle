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
 * @template TSaveDTO of array<string,mixed>|object
 * @template-default TSaveDTO=T
 * @template-extends DeleteElement<T>
 * @template-extends FormElement<T, TSaveDTO>
 * @template-extends ListElement<T>
 */
interface CRUDElement extends ListElement, FormElement, DeleteElement
{
}
