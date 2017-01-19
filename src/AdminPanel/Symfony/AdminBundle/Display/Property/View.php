<?php


namespace AdminPanel\Symfony\AdminBundle\Display\Property;

class View
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var null|string
     */
    private $label;

    /**
     * @var string
     */
    private $path;

    /**
     * @param mixed $value
     * @param string $path
     * @param null|string $label
     */
    public function __construct($value, $path, $label = null)
    {
        $this->value = $value;
        $this->label = $label;
        $this->path = $path;
    }

    /**
     * @return string
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
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
