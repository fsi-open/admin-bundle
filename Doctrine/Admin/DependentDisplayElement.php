<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\DependentElementImpl;

/**
 * @template T of object
 * @template-extends DisplayElement<T>
 */
abstract class DependentDisplayElement extends DisplayElement implements DependentElement
{
    use DependentElementImpl;

    public function getRouteParameters(): array
    {
        return array_merge(
            parent::getRouteParameters(),
            [DependentElement::PARENT_REQUEST_PARAMETER => $this->getParentObjectId()]
        );
    }
}
