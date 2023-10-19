<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\ResourceRepository\Form;

use FSi\Bundle\ResourceRepositoryBundle\Form\Type\ResourceType;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class ResourceTypeExtension extends AbstractTypeExtension
{
    private MapBuilder $mapBuilder;

    public function __construct(MapBuilder $mapBuilder)
    {
        $this->mapBuilder = $mapBuilder;
    }

    /**
     * @return list<string>
     */
    public static function getExtendedTypes(): iterable
    {
        return [ResourceType::class];
    }

    public function getExtendedType(): string
    {
        return ResourceType::class;
    }

    /**
     * @param FormView<FormView> $view
     * @param FormInterface<FormInterface> $form
     * @param array<string, mixed> $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $resource = $this->mapBuilder->getResource($options['resource_key']);
        $translatable = $options['resource_key'] !== $resource->getName();
        foreach ($view->children as $child) {
            $child->vars['translatable'] = $translatable;
        }
    }
}
