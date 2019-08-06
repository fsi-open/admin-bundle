<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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

    public function __construct(string $name, Element $element)
    {
        parent::__construct($name);

        $this->element = $element;
    }

    public function getElement(): Element
    {
        return $this->element;
    }

    public function getRoute(): string
    {
        return $this->element->getRoute();
    }

    public function getRouteParameters(): array
    {
        return $this->element->getRouteParameters();
    }

    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults(['elements' => []]);
        $optionsResolver->setAllowedTypes('elements', ['array']);
        $optionsResolver->setNormalizer('elements', function (Options $options, array $value) {
            foreach ($value as $element) {
                if (false === $element instanceof Element) {
                    throw new InvalidOptionsException(sprintf(
                        'Instance of "%s" expected but got "%s"',
                        Element::class,
                        is_object($element) ? get_class($element) : gettype($element)
                    ));
                }
            }

            return $value;
        });
    }
}
