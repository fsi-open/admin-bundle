<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin;

use FSi\Bundle\AdminBundle\Exception\MissingOptionException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractElement implements Element
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $unresolvedOptions;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->unresolvedOptions = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteParameters()
    {
        return [
            'element' => $this->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name)
    {
        $this->resolveOptions();
        if (!$this->hasOption($name)) {
            throw new MissingOptionException(sprintf(
                'Option with name "%s" does not exist in element "%s"',
                $name,
                get_class($this)
            ));
        }

        return $this->options[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        $this->resolveOptions();
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($name)
    {
        $this->resolveOptions();
        return isset($this->options[$name]);
    }

    private function resolveOptions()
    {
        if (!is_array($this->options)) {
            $optionsResolver = new OptionsResolver();
            $this->configureOptions($optionsResolver);
            $this->options = $optionsResolver->resolve($this->unresolvedOptions);
            unset($this->unresolvedOptions);
        }
    }
}
