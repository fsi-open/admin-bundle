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
    private $path;

    /**
     * @param mixed $value
     * @param $path
     * @param null|string $label
     */
    public function __construct($value, $path, $label = null)
    {
        $this->value = $value;
        $this->label = $label;
        $this->path = $path;
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

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
}
