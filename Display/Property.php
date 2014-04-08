<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property\ValueDecorator;

class Property
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var null|string
     */
    private $label;

    /**
     * @var array
     */
    private $valueDecorators;

    /**
     * @param string $path
     * @param null|string $label
     * @param array $valueDecorators
     */
    public function __construct($path, $label = null, $valueDecorators = array())
    {
        $this->validatePath($path);
        $this->validateDecorators($valueDecorators);

        $this->path = $path;
        $this->label = $label;
        $this->valueDecorators = $valueDecorators;
    }

    /**
     * @return null|string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getValueDecorators()
    {
        return $this->valueDecorators;
    }

    /**
     * @param $path
     * @throws \InvalidArgumentException
     */
    private function validatePath($path)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException("Property path must be a string value");
        }
    }

    /**
     * @param $viewDecorators
     * @throws \InvalidArgumentException
     */
    private function validateDecorators($viewDecorators)
    {
        foreach ($viewDecorators as $decorator) {
            if (!$decorator instanceof ValueDecorator) {
                throw new \InvalidArgumentException("All property value decorators must implement ValueDecorator interface");
            }
        }
    }
}
