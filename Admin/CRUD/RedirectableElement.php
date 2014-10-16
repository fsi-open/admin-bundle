<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\Element;

interface RedirectableElement extends Element
{
    /**
     * Return route name that will be used to redirect after successful form handling.
     *
     * @return string
     */
    public function getSuccessRoute();

    /**
     * Return array of parameters used with route returned from getSuccessRoute().
     *
     * @return array
     */
    public function getSuccessRouteParameters();
}
