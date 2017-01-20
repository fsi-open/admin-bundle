<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Doctrine\ORM;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Component\DataSource\Driver\DriverFactoryInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * {@inheritdoc}
 */
class DoctrineFactory implements DriverFactoryInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    private $registry;

    /**
     * Array of extensions.
     *
     * @var array
     */
    private $extensions;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $optionsResolver;

    /**
     * {@inheritdoc}
     */
    public function __construct(ManagerRegistry $registry, $extensions = array())
    {
        $this->registry = $registry;
        $this->extensions = $extensions;
        $this->optionsResolver = new OptionsResolver();
        $this->initOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverType()
    {
        return 'doctrine-orm';
    }

    /**
     * {@inheritdoc}
     */
    public function createDriver($options = array())
    {
        $options = $this->optionsResolver->resolve($options);

        if (empty($options['em'])) {
            $em = $this->registry->getManager($this->registry->getDefaultManagerName());
        } else {
            $em = $this->registry->getManager($options['em']);
        }

        $entity = isset($options['entity']) ? $options['entity'] : $options['qb'];

        return new DoctrineDriver($this->extensions, $em, $entity, $options['alias']);
    }

    /**
     * Initialize Options Resolvers for driver and datasource builder.
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    private function initOptions()
    {
        $this->optionsResolver->setDefaults(array(
            'entity' => null,
            'qb' => null,
            'alias' => null,
            'em' => null,
        ));

        $this->optionsResolver->setAllowedTypes('entity', array('string', 'null'));
        $this->optionsResolver->setAllowedTypes('qb', array('\Doctrine\ORM\QueryBuilder', 'null'));
        $this->optionsResolver->setAllowedTypes('alias', array('null', 'string'));
        $this->optionsResolver->setAllowedTypes('em', array('null', 'string'));

        $entityNormalizer = function(Options $options, $value) {
            if (is_null($options['qb']) && is_null($value)) {
                throw new InvalidOptionsException('You must specify at least one option, "qb" or "entity".');
            }

            return $value;
        };

        $this->optionsResolver->setNormalizer('entity', $entityNormalizer);
    }
}
