<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin;

interface RedirectableElement extends Element
{
    public function getSuccessRoute(): string;

    /**
     * @return array<string,mixed>
     */
    public function getSuccessRouteParameters(): array;
}
