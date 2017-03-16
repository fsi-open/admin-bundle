<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property;

class SimpleDisplay implements Display
{
    /**
     * @var Property[]|array
     */
    private $data = [];

    /**
     * {@inheritdoc}
     */
    public function add($value, $label, array $valueFormatters = [])
    {
        $this->data[] = new Property($value, $label, $valueFormatters);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }
}
