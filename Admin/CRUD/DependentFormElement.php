<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\DependentElementImpl;

/**
 * @template T of array<string,mixed>|object
 * @template TSaveDTO of array<string,mixed>|object
 * @template TParent of array<string,mixed>|object
 * @template-default TSaveDTO=T
 * @template-extends GenericFormElement<T, TSaveDTO>
 * @template-implements DependentElement<TParent>
 */
abstract class DependentFormElement extends GenericFormElement implements DependentElement
{
    /** @use DependentElementImpl<TParent> */
    use DependentElementImpl;

    public function getRouteParameters(): array
    {
        return array_merge(
            parent::getRouteParameters(),
            [DependentElement::PARENT_REQUEST_PARAMETER => $this->getParentObjectId()]
        );
    }
}
