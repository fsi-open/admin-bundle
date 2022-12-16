<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;

/**
 * @template TParent of array<string,mixed>|object
 */
interface DependentElement extends Element, RequestStackAware
{
    public const PARENT_REQUEST_PARAMETER = 'parent';

    /**
     * ID of parent element
     */
    public function getParentId(): string;

    /**
     * @param DataIndexerElement<TParent> $element
     */
    public function setParentElement(DataIndexerElement $element): void;

    /**
     * @return TParent|null
     */
    public function getParentObject();
}
