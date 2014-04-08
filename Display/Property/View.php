<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display\Property;

class View
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var null|string
     */
    protected $label;

    /**
     * @param mixed $value
     * @param null|string $label
     * @throws \InvalidArgumentException
     */
    public function __construct($value, $label = null)
    {
        $this->value = $value;
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
