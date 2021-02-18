<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\Manager\Visitor;

interface ManagerInterface
{
    public function addElement(Element $element): void;

    public function hasElement(string $id): bool;

    public function getElement(string $id): Element;

    public function removeElement(string $id): void;

    /**
     * @return array<Element>
     */
    public function getElements(): array;
}
