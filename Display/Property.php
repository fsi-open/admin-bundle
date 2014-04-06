<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display;

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
     * @param string $path
     * @param null|string $label
     * @throws \InvalidArgumentException
     */
    public function __construct($path, $label = null)
    {
        $this->validatePath($path);

        $this->path = $path;
        $this->label = $label;
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
     * @param $path
     * @throws \InvalidArgumentException
     */
    private function validatePath($path)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException("Property path must be a string value");
        }
    }
}
