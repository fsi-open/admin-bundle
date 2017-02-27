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
     * @param string $path
     * @param string|null $label
     * @param array|\FSi\Bundle\AdminBundle\Display\Property\ValueFormatter[] $valueFormatters
     * @return \FSi\Bundle\AdminBundle\Display\Display
     */
    public function add($path, $label = null, $valueFormatters = []);

    /**
     * @return \FSi\Bundle\AdminBundle\Display\DisplayView
     */
    public function createView();
}
