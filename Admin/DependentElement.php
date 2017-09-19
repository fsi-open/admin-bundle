<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin;

interface DependentElement extends Element, RequestStackAware
{
    const PARENT_REQUEST_PARAMETER = 'parent';

    /**
     * ID of parent element
     */
    public function getParentId(): string;

    public function setParentElement(Element $element): void;

    /**
     * @return object|null
     */
    public function getParentObject();
}
