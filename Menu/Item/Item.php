<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Menu\Item;

use FSi\Bundle\AdminBundle\Exception\MissingOptionException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_key_exists;

class Item
{
    private string $name;

    private ?string $label = null;

    /**
     * @var array<Item>
     */
    private array $children;

    /**
     * @var array<string,mixed>
     */
    private array $options;

    public function __construct(?string $name = null)
    {
        $this->children = [];
        $this->name = $name ?? '';

        $this->setOptions([]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    public function addChild(Item $item): void
    {
        $this->children[$item->getName()] = $item;
    }

    public function removeChild(string $name): void
    {
        if (true === array_key_exists($name, $this->children)) {
            unset($this->children[$name]);
        }
    }

    public function hasChildren(): bool
    {
        return (bool) count($this->children);
    }

    /**
     * @return array<Item>
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @param array<string,mixed> $options
     */
    public function setOptions(array $options): void
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);
        $this->options = $optionsResolver->resolve($options);
    }

    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption(string $name)
    {
        if (false === $this->hasOption($name)) {
            throw new MissingOptionException(sprintf('Option with name: "%s" does\'t exists.', $name));
        }

        return $this->options[$name];
    }

    /**
     * @return array<string,mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'attr' => [],
        ]);

        $optionsResolver->setAllowedTypes('attr', ['array']);

        $optionsResolver->setNormalizer('attr', function (Options $options, array $value) {
            $attrOptionsResolver = new OptionsResolver();
            $attrOptionsResolver->setDefaults([
                'id' => null,
                'class' => null,
            ]);

            $attrOptionsResolver->setAllowedTypes('id', ['null', 'string']);
            $attrOptionsResolver->setAllowedTypes('class', ['null', 'string']);

            return $attrOptionsResolver->resolve($value);
        });
    }
}
