<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin;

use FSi\Bundle\AdminBundle\Exception\MissingOptionException;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_key_exists;

abstract class AbstractElement implements Element
{
    /**
     * @var array<string,mixed>|null
     */
    private ?array $options = null;

    /**
     * @var array<string,mixed>
     */
    private array $unresolvedOptions;

    /**
     * @param array<string,mixed> $options
     */
    public function __construct(array $options = [])
    {
        $this->unresolvedOptions = $options;
    }

    public function getRouteParameters(): array
    {
        return [
            'element' => $this->getId(),
        ];
    }

    public function getOption(string $name)
    {
        $this->options = $this->resolveOptions();

        if (false === $this->hasOption($name)) {
            throw new MissingOptionException(sprintf(
                'Option with name "%s" does not exist in element "%s"',
                $name,
                get_class($this)
            ));
        }

        return $this->options[$name];
    }

    public function getOptions(): array
    {
        $this->options = $this->resolveOptions();

        return $this->options;
    }

    public function hasOption(string $name): bool
    {
        $this->options = $this->resolveOptions();

        return true === array_key_exists($name, $this->options) && null !== $this->options[$name];
    }

    /**
     * @return array<string,mixed>
     */
    private function resolveOptions(): array
    {
        if (null !== $this->options) {
            return $this->options;
        }

        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);
        $this->options = $optionsResolver->resolve($this->unresolvedOptions);

        return $this->options;
    }
}
