<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\LocaleProviderAware;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;

/**
 * @template T of array<string,mixed>|object
 * @template-extends DataIndexerElement<T>
 */
interface BatchElement extends DataIndexerElement, LocaleProviderAware, RedirectableElement
{
    /**
     * @param T $data
     */
    public function apply($data): void;
}
