<?php

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ResourceFormBuilder
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var \FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder
     */
    protected $mapBuilder;

    public function __construct(FormFactoryInterface $formFactory, MapBuilder $mapBuilder)
    {
        $this->formFactory = $formFactory;
        $this->mapBuilder = $mapBuilder;
    }

    public function build(GenericResourceElement $element)
    {
        $resources = $this->getResourceGroup($element->getKey());

        $builder = $this->formFactory->createBuilder(
            'form',
            $this->createFormData($resources, $element->getRepository()),
            $element->getResourceFormOptions()
        );

        $this->buildForm($builder, $resources);
        return $builder->getForm();
    }

    /**
     * @param string $key
     * @return array
     */
    private function getResourceGroup($key)
    {
        $map = $this->mapBuilder->getMap();
        $propertyPath = $this->createPropertyPath($key);
        $accessor = PropertyAccess::createPropertyAccessor();

        $resources = $accessor->getValue($map, $propertyPath);
        if (!is_array($resources)) {
            throw new RuntimeException(sprintf('%s its not a resource group key', $key));
        }

        return $resources;
    }

    /**
     * @param $key
     * @return string
     */
    private function createPropertyPath($key)
    {
        $parts = explode('.', $key);
        $propertyPath = '';

        foreach ($parts as $part) {
            $propertyPath .= sprintf("[%s]", $part);
        }

        return $propertyPath;
    }

    /**
     * @param array|\FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface[] $resources
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository $valueRepository
     * @return array
     */
    private function createFormData(array $resources, ResourceValueRepository $valueRepository)
    {
        $data = array();

        foreach ($resources as $resource) {
            $data[$this->normalizeKey($resource->getName())] = $valueRepository->get($resource->getName());
        }

        return $data;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array|\FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface[] $resources
     */
    private function buildForm(FormBuilderInterface $builder, array $resources)
    {
        foreach ($resources as $resource) {
            $builder->add(
                $this->normalizeKey($resource->getName()),
                'resource',
                array(
                    'resource_key' => $resource->getName(),
                )
            );
        }
    }

    /**
     * @param string $key
     * @return string
     */
    private function normalizeKey($key)
    {
        return str_replace('.', '_', $key);
    }
}
