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
     * @param mixed $value
     * @param string $label
     * @param \FSi\Bundle\AdminBundle\Display\Property\ValueFormatter[]|null $valueFormatters
     */
    public function add($value, $label, array $valueFormatters = []);

    /**
     * @return \FSi\Bundle\AdminBundle\Display\Property[]|array
     */
    public function getData();
}
