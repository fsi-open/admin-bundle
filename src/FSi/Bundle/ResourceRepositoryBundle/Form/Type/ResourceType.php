<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\ResourceRepositoryBundle\Form\Type;

use FSi\Bundle\ResourceRepositoryBundle\Exception\ResourceFormTypeException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResourceType extends AbstractType
{
    /**
     * @var MapBuilder
     */
    protected $mapBuilder;

    /**
     * @var string
     */
    protected $resourceClass;

    /**
     * @param MapBuilder $mapBuilder
     * @param $resourceClass
     */
    public function __construct(MapBuilder $mapBuilder, $resourceClass)
    {
        $this->mapBuilder = $mapBuilder;
        $this->resourceClass = $resourceClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'resource';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->resourceClass
            )
        );

        $resolver->setRequired(
            array(
                'resource_key'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$this->mapBuilder->hasResource($options['resource_key'])) {
            throw new ResourceFormTypeException(sprintf('"%s" is not a valid resource key', $options['resource_key']));
        }

        $resource = $this->mapBuilder->getResource($options['resource_key']);
        $resourceFormBuilder = $resource->getFormBuilder($builder->getFormFactory());

        $builder->add($resourceFormBuilder);
    }
}
