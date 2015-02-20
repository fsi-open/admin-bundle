<?php

namespace FSi\Bundle\AdminBundle\Menu\Item;

use FSi\Bundle\AdminBundle\Exception\MissingOptionException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Item
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var Item[]
     */
    private $children;

    /**
     * @var array
     */
    private $options;

    /**
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        $this->children = array();
        $this->name = $name;

        $this->setOptions(array());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @param Item $item
     */
    public function addChild(Item $item)
    {
        $this->children[$item->getName()] = $item;
    }

    /**
     * @param string $name
     */
    public function removeChild($name)
    {
        if (isset($this->children[$name])) {
            unset($this->children[$name]);
        }
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return (boolean) count($this->children);
    }

    /**
     * @return Item[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    public function setOptions(array $options)
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults(array(
            'attr' => array(),
        ));

        $optionsResolver->setAllowedTypes(array(
            'attr' => array('array'),
        ));

        $options = $optionsResolver->resolve($options);

        $attrOptionsResolver = new OptionsResolver();
        $attrOptionsResolver->setDefaults(array(
            'id' => null,
            'class' => null,
        ));

        $attrOptionsResolver->setAllowedTypes(array(
            'id' => array('null', 'string'),
            'class' => array('null', 'string'),
        ));

        $this->options = $attrOptionsResolver->resolve($options['attr']);
    }

    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    public function getOption($name)
    {
        if (!$this->hasOption($name)) {
            throw new MissingOptionException(sprintf('Option with name: "%s" does\'t exists.', $name));
        }

        return $this->options[$name];
    }

    public function getOptions()
    {
        return $this->options;
    }
}
