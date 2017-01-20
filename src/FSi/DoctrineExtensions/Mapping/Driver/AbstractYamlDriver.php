<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Mapping\Driver;

use FSi\Component\Metadata\ClassMetadataInterface;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractYamlDriver extends AbstractFileDriver
{
    /**
     * @param \FSi\Component\Metadata\ClassMetadataInterface $extendedClassMetadata
     * @return array
     */
    protected function getFileMapping(ClassMetadataInterface $extendedClassMetadata)
    {
        $element = Yaml::parse(file_get_contents($this->findMappingFile($extendedClassMetadata)));
        if (isset($element[$extendedClassMetadata->getClassName()])) {
            return $element[$extendedClassMetadata->getClassName()];
        } else {
            return array();
        }
    }
}
