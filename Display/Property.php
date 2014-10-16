<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property\ValueFormatter;

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
     * @param array|\FSi\Bundle\AdminBundle\Display\Property\ValueFormatter[] $valueFormatters
     */
    public function __construct($path, $label = null, $valueFormatters = array())
    {
        $this->validatePath($path);
        $this->validateFormatters($valueFormatters);

        $this->path = $path;
        $this->label = $label;
        $this->valueDecorators = $valueFormatters;
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
     * @return array|\FSi\Bundle\AdminBundle\Display\Property\ValueFormatter[]
     */
    public function getValueFormatters()
    {
        return $this->valueDecorators;
    }

    /**
     * @param string $path
     * @throws \InvalidArgumentException
     */
    private function validatePath($path)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException("Property path must be a string value");
        }
    }

    /**
     * @param array|\FSi\Bundle\AdminBundle\Display\Property\ValueFormatter[] $valueFormatters
     * @throws \InvalidArgumentException
     */
    private function validateFormatters(array $valueFormatters)
    {
        foreach ($valueFormatters as $formatter) {
            if (!$formatter instanceof ValueFormatter) {
                throw new \InvalidArgumentException("All property value formatters must implement ValueFormatter interface");
            }
        }
    }
}
