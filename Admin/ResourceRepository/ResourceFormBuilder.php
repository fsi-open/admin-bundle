<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\ResourceRepositoryBundle\Form\Type\ResourceType;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ResourceFormBuilder
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var MapBuilder
     */
    protected $mapBuilder;

    public function __construct(FormFactoryInterface $formFactory, MapBuilder $mapBuilder)
    {
        $this->formFactory = $formFactory;
        $this->mapBuilder = $mapBuilder;
    }

    public function build(Element $element): FormInterface
    {
        $resources = $this->getResourceGroup($element->getKey());

        $builder = $this->formFactory->createBuilder(
            FormType::class,
            $this->createFormData($element, $element->getResourceValueRepository(), $resources),
            $element->getResourceFormOptions()
        );

        $this->buildForm($element, $builder, $resources);

        return $builder->getForm();
    }

    private function getResourceGroup(string $key): array
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

    private function createPropertyPath(string $key): string
    {
        $parts = explode('.', $key);
        $propertyPath = '';

        foreach ($parts as $part) {
            $propertyPath .= sprintf('[%s]', $part);
        }

        return $propertyPath;
    }

    /**
     * @param Element $element
     * @param ResourceValueRepository $valueRepository
     * @param array<ResourceInterface> $resources
     * @return array
     */
    private function createFormData(
        Element $element,
        ResourceValueRepository $valueRepository,
        array $resources
    ): array {
        $data = [];

        foreach ($resources as $resourceKey => $resource) {
            $resourceName = $this->buildResourceName($element, $resourceKey);
            $data[$this->normalizeKey($resourceName)] = $valueRepository->get($resource->getName());
        }

        return $data;
    }

    /**
     * @param Element $element
     * @param FormBuilderInterface $builder
     * @param ResourceInterface[] $resources
     */
    private function buildForm(
        Element $element,
        FormBuilderInterface $builder,
        array $resources
    ): void {
        foreach ($resources as $resourceKey => $resource) {
            $resourceName = $this->buildResourceName($element, $resourceKey);
            $builder->add(
                $this->normalizeKey($resourceName),
                ResourceType::class,
                ['resource_key' => $resourceName]
            );
        }
    }

    private function normalizeKey(string $key): string
    {
        return str_replace('.', '_', $key);
    }

    private function buildResourceName(Element $element, string $resourceKey): string
    {
        return sprintf('%s.%s', $element->getKey(), $resourceKey);
    }
}
