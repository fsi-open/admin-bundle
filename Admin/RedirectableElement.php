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
    /**
     * Return route name that will be used to redirect after successful form handling.
     */
    public function getSuccessRoute(): string;

    /**
     * Return array of parameters used with route returned from getSuccessRoute().
     */
    public function getSuccessRouteParameters(): array;
}
