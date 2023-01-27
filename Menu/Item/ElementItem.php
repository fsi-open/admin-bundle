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

use function array_merge;

class ElementItem extends RoutableItem
{
    private Element $element;

    /**
     * @param array<string, mixed> $routeParameters
     */
    public function __construct(string $name, Element $element, array $routeParameters = [])
    {
        parent::__construct(
            $name,
            $element->getRoute(),
            array_merge($element->getRouteParameters(), $routeParameters)
        );

        $this->element = $element;
    }

    public function getElement(): Element
    {
        return $this->element;
    }

    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefault('elements', []);
        $optionsResolver->setAllowedTypes('elements', ['array']);
        $optionsResolver->setNormalizer('elements', function (Options $options, array $value): array {
            foreach ($value as $element) {
                if (false === $element instanceof Element) {
                    throw new InvalidOptionsException(sprintf(
                        'Instance of %s expected but got %s',
                        Element::class,
                        is_object($element) ? get_class($element) : gettype($element)
                    ));
                }
            }

            return $value;
        });
    }
}
