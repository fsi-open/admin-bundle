<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Uploadable\Mapping\Driver;

use FSi\DoctrineExtensions\Mapping\Driver\AbstractYamlDriver;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use FSi\Component\Metadata\ClassMetadataInterface;
use FSi\DoctrineExtensions\Uploadable\Exception\MappingException;

class Yaml extends AbstractYamlDriver
{
    /**
     * {@inheritdoc}
     */
    protected function loadExtendedClassMetadata(ClassMetadata $baseClassMetadata, ClassMetadataInterface $extendedClassMetadata)
    {
        $mapping = $this->getFileMapping($extendedClassMetadata);

        if (isset($mapping['type']) && ($mapping['type'] == 'entity') && isset($mapping['fields']) && is_array($mapping['fields'])) {
            foreach ($mapping['fields'] as $field => $config) {
                if (isset($config['fsi']) && is_array($config['fsi']) && isset($config['fsi']['uploadable'])) {
                    $uploadable = $config['fsi']['uploadable'];
                    if (!is_array($uploadable)) {
                        throw new MappingException(sprintf('Wrong "uploadable" format for "%s" field in "%s" entity.', $field, $extendedClassMetadata->getClassName()));
                    }
                    $extendedClassMetadata->addUploadableProperty(
                        $field,
                        $this->getValue($uploadable, 'targetField'),
                        $this->getValue($uploadable, 'filesystem'),
                        $this->getValue($uploadable, 'keymaker'),
                        $this->getValue($uploadable, 'keyLength'),
                        $this->getValue($uploadable, 'keyPattern')
                    );
                }
            }
        }
    }

    /**
     * @param array $array
     * @return mixed
     */
    private function getValue(array $array, $key)
    {
        return isset($array[$key]) ? $array[$key] : null;
    }
}
