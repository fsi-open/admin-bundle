<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\RedirectableElement;

interface BatchElement extends DataIndexerElement, RedirectableElement
{
    /**
     * This method is called during batch action for every single data item.
     *
     * @param mixed $data
     */
    public function apply($data): void;
}
