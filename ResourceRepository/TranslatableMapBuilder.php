<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\ResourceRepository;

use FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface;
use FSi\Component\Translatable\LocaleProvider;
use RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Yaml\Yaml;

use function array_key_exists;
use function array_reduce;
use function file_get_contents;
use function in_array;
use function sprintf;
use function strlen;
use function substr;

final class TranslatableMapBuilder extends MapBuilder
{
    private LocaleProvider $localeProvider;
    private ?PropertyAccessorInterface $propertyAccessor;
    /**
     * @var array<array-key, string>
     */
    private array $validOptionKeys;
    private string $mapPath;

    /**
     * @param array<string, class-string<ResourceInterface>> $resourceTypes
     * @param array<array-key, string> $validOptionKeys
     */
    public function __construct(
        LocaleProvider $localeProvider,
        string $mapPath,
        array $resourceTypes,
        array $validOptionKeys = ['form_options', 'constraints', 'translatable']
    ) {
        $this->localeProvider = $localeProvider;
        $this->validOptionKeys = $validOptionKeys;
        $this->mapPath = $mapPath;
        $this->propertyAccessor = null;
        $this->resourceTypes = [];
        $this->resources = [];
        $this->map = [];

        foreach ($resourceTypes as $type => $class) {
            $this->resourceTypes[$type] = $class;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getMap(): array
    {
        $locale = $this->localeProvider->getLocale();
        if (false === array_key_exists($locale, $this->map)) {
            $this->map[$locale] = $this->loadYamlMap($this->mapPath);
        }

        return $this->map[$locale];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getResource(string $key)
    {
        return $this->getResourceFromMap($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasResource($key): bool
    {
        $resource = $this->getResourceFromMap($key);
        return null !== $resource && '' !== $resource;
    }

    /**
     * @param array<string, mixed> $configuration
     */
    protected function createResource(array $configuration, string $path): ResourceInterface
    {
        $locale = $this->localeProvider->getLocale();
        if (true === $this->isTranslatable($configuration)) {
            $path = "{$path}.{$locale}";
        }

        return parent::createResource($configuration, $path);
    }

    /**
     * @param array<string, mixed> $configuration
     */
    protected function validateConfiguration(array $configuration, string $path): void
    {
        if (255 < strlen($path)) {
            throw new ConfigurationException(sprintf(
                '"%s..." key is too long. Maximum key length is 255 characters',
                substr($path, 0, 32)
            ));
        }

        if (false === array_key_exists('type', $configuration)) {
            throw new ConfigurationException(
                "Missing \"type\" declaration in \"{$path}\" element configuration"
            );
        }
    }

    /**
     * @param array<string, mixed> $configuration
     */
    protected function validateResourceConfiguration(array $configuration): void
    {
        foreach ($configuration as $key => $options) {
            if ('type' === $key) {
                continue;
            }

            if ('translatable' === $key && false === is_bool($options)) {
                throw new ConfigurationException(
                    'Invalid value of "translatable" option. This option accepts only boolean value.'
                );
            }

            if (false === in_array($key, $this->validOptionKeys, true)) {
                throw new ConfigurationException(
                    sprintf(
                        '"%s" is not a valid resource type option. Try one from: %s',
                        $key,
                        implode(', ', $this->validOptionKeys)
                    )
                );
            }
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getResourceFromMap(string $key)
    {
        $propertyPath = array_reduce(
            explode('.', $key),
            static fn(string $accumulator, string $part): string
                => "{$accumulator}[{$part}]",
            ''
        );

        if (null === $this->propertyAccessor) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->propertyAccessor->getValue($this->getMap(), $propertyPath);
    }

    /**
     * @param array<string, mixed> $configuration
     */
    private function isTranslatable(array $configuration): bool
    {
        return true === array_key_exists('translatable', $configuration)
            && true === $configuration['translatable']
        ;
    }

    /**
     * @return array<string, mixed>
     * @throws RuntimeException
     */
    private function loadYamlMap(string $mapPath): array
    {
        $fileContents = file_get_contents($mapPath);
        if (false === $fileContents) {
            throw new RuntimeException("Resource map file at path \"{$mapPath}\" does not exist!");
        }

        /** @var array<string, mixed> $map */
        $map = $this->recursiveParseRawMap(Yaml::parse($fileContents));
        return $map;
    }
}
