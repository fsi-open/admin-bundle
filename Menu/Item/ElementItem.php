<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Item;

use FSi\Bundle\AdminBundle\Admin\Element;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElementItem extends RoutableItem
{
    /**
     * @var Element
     */
    private $element;

    public function __construct($name, Element $element)
    {
        parent::__construct($name);

        $this->element = $element;
    }

    /**
     * @return Element
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @return bool
     */
    public function hasElement()
    {
        return isset($this->element);
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->element->getRoute();
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->element->getRouteParameters();
    }

    protected function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults(array(
            'elements' => array(),
        ));

        $optionsResolver->setAllowedTypes('elements', array('array'));

        $optionsResolver->setNormalizer('elements', function (Options $options, array $value) {
            foreach ($value as $element) {
                if (!($element instanceof Element)) {
                    throw new InvalidOptionsException(sprintf(
                        'Instance of FSi\Bundle\AdminBundle\Admin\Element expected but got %s',
                        is_object($element) ? get_class($element) : gettype($element)
                    ));
                }
            }

            return $value;
        });
    }
}
