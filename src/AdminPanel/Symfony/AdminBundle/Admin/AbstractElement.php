<?php

namespace AdminPanel\Symfony\AdminBundle\Admin;

use AdminPanel\Symfony\AdminBundle\Exception\MissingOptionException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractElement implements Element
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $optionsResolver = new OptionsResolver();
        $this->setDefaultOptions($optionsResolver);
        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteParameters()
    {
        return array(
            'element' => $this->getId(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name)
    {
        if (!$this->hasOption($name)) {
            throw new MissingOptionException(sprintf('Option with name: "%s" does\'t exists.', $name));
        }

        return $this->options[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }
}
