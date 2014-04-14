<?php

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface as ResourceTypeInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormBuilderInterface;
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
     * @return mixed
     */
    private function getResourceGroup($key)
    {
        $map = $this->mapBuilder->getMap();

        $parts = explode('.', $key);
        $propertyPath = '';

        foreach ($parts as $part) {
            $propertyPath .= sprintf("[%s]", $part);
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $resources = $accessor->getValue($map, $propertyPath);

        if (!is_array($resources)) {
            throw new RuntimeException(sprintf('%s its not a resource group key', $key));
        }

        return $resources;
    }

    /**
     * @param array $resources
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository $valueRepository
     * @return array
     */
    private function createFormData(array $resources, ResourceValueRepository $valueRepository)
    {
        $data = array();

        foreach ($resources as $resource) {
            if ($resource instanceof ResourceTypeInterface) {
                $data[$this->normalizeKey($resource->getName())] = $valueRepository->get($resource->getName());
            }
        }

        return $data;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $resources
     */
    private function buildForm(FormBuilderInterface $builder, array $resources)
    {
        foreach ($resources as $resource) {
            if ($resource instanceof ResourceTypeInterface) {
                $builder->add(
                    $this->normalizeKey($resource->getName()),
                    'resource',
                    array(
                        'resource_key' => $resource->getName(),
                    )
                );
            }
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
