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
     * @param $path
     * @param null $label
     * @param array $valueFormatters
     * @return Display
     */
    public function add($path, $label = null, $valueFormatters = array());

    /**
     * @return DisplayView
     */
    public function createView();
}
