<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\Element;

class CRUDListElementContext extends ListElementContext
{
    protected function supportsElement(Element $element): bool
    {
        return $element instanceof CRUDElement;
    }
}
