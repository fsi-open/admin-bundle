<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\ResourceRepositoryBundle\Repository;

use FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface;
use Symfony\Component\Yaml\Yaml;

class MapBuilder
{
    /**
     * Template used to create constraint object
     */
    const CONSTRAINT_CLASS = 'Symfony\\Component\\Validator\\Constraints\\%s';

    /**
     * @var array
     */
    protected $rawArray;

    /**
     * Parser resources map
     *
     * @var array
     */
    protected $map;

    /**
     * Array that holds every single resource under unique key
     *
     * @var array
     */
    protected $resources;

    /**
     * @var string[]
     */
    protected $resourceTypes;

    /**
     * @param string $mapPath
     * @param string[] $resourceTypes
     */
    public function __construct($mapPath, $resourceTypes = array())
    {
        $this->resourceTypes = array();
        $this->resources = array();

        foreach ($resourceTypes as $type => $class) {
            $this->resourceTypes[$type] = $class;
        }

        $this->map = $this->recursiveParseRawMap(Yaml::parse(file_get_contents($mapPath)));
    }

    /**
     * Return nested array where keys represent groups and values are resource types.
     *
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Get resource definition by key.
     * It can return resource definition object or array if key represents resources group
     *
     * @param $key
     * @return mixed
     */
    public function getResource($key)
    {
        return $this->resources[$key];
    }

    /**
     * Check if resource definition exists in map
     *
     * @param $key
     * @return bool
     */
    public function hasResource($key)
    {
        return array_key_exists($key, $this->resources);
    }

    /**
     * @param array $rawMap
     * @param null|string $parentPath
     * @throws \FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException
     * @return array
     */
    protected function recursiveParseRawMap($rawMap = array(), $parentPath = null)
    {
        $map = array();

        if (!is_array($rawMap)) {
            return $map;
        }

        foreach ($rawMap as $key => $configuration) {
            $path = (isset($parentPath))
                ? $parentPath . '.' . $key
                : $key;

            $this->validateConfiguration($configuration, $path);

            if ($configuration['type'] == 'group') {
                unset($configuration['type']);
                $map[$key] = $this->recursiveParseRawMap($configuration, $path);
                continue;
            }

            $this->validateResourceConfiguration($configuration);

            $resource = $this->createResource($configuration, $path);
            $this->addConstraints($resource, $configuration);
            $this->setFormOptions($resource, $configuration);

            $map[$key] = $resource;
            $this->resources[$path] = $map[$key];
        }

        return $map;
    }

    /**
     * @param array $configuration
     * @param string $path
     * @throws \FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException
     * @return ResourceInterface
     */
    protected function createResource($configuration, $path)
    {
        $type = $configuration['type'];

        if (!array_key_exists($type, $this->resourceTypes)) {
            throw new ConfigurationException(
                sprintf('"%s" is not a valid resource type. Try one from: %s', $type, implode(', ', array_keys($this->resourceTypes)))
            );
        }

        $class = $this->resourceTypes[$type];

        return new $class($path);
    }

    /**
     * @param ResourceInterface $resource
     * @param $configuration
     */
    protected function addConstraints(ResourceInterface $resource, $configuration)
    {
        if (isset($configuration['constraints'])) {
            $constraints = $configuration['constraints'];

            foreach ($constraints as $constraint => $constraintOptions) {
                if (!class_exists($constraint)) {
                    $constraint = sprintf(self::CONSTRAINT_CLASS, ucfirst($constraint));
                }

                $resource->addConstraint(new $constraint($constraintOptions));
            }
        }
    }

    protected function setFormOptions(ResourceInterface $resource, $configuration)
    {
        if (isset($configuration['form_options']) && is_array($configuration['form_options'])) {
            $resource->setFormOptions($configuration['form_options']);
        }
    }

    /**
     * @param $configuration
     * @param $path
     * @throws \FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException
     */
    protected function validateConfiguration($configuration, $path)
    {
        if (strlen($path) > 255) {
            throw new ConfigurationException(
                sprintf('"%s..." key is too long. Maximum key length is 255 characters', substr($path, 0, 32))
            );
        }

        if (!array_key_exists('type', $configuration)) {
            throw new ConfigurationException(
                sprintf('Missing "type" declaration in "%s" element configuration', $path)
            );
        }

    }

    /**
     * @param $configuration
     * @throws \FSi\Bundle\ResourceRepositoryBundle\Exception\ConfigurationException
     */
    protected function validateResourceConfiguration($configuration)
    {
        $validKeys = array(
            'form_options',
            'constraints'
        );

        foreach ($configuration as $key => $options) {
            if ($key === 'type') {
                continue;
            }

            if (!in_array($key, $validKeys)) {
                throw new ConfigurationException(
                    sprintf('"%s" is not a valid resource type option. Try one from: %s', $key, implode(', ', $validKeys))
                );
            }
        }
    }
}
