<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display;

interface Display
{
    /**
     * @param Property $property
     */
    public function add(Property $property);

    /**
     * @return DisplayView
     */
    public function createView();
}
