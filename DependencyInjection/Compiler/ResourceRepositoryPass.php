<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder;
use FSi\Bundle\AdminBundle\EventSubscriber\TranslationLocaleMenuSubscriber;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResourceRepositoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (true === $container->hasExtension('fsi_resource_repository')) {
            $container->getDefinition(TranslationLocaleMenuSubscriber::class)->setArgument(
                '$resourceRepositoryClass',
                '%fsi_resource_repository.resource.value.class%'
            );
        } else {
            $container->removeDefinition(ResourceRepositoryContext::class);
            $container->removeDefinition(MapBuilder::class);
            $container->removeDefinition(ResourceFormBuilder::class);
        }
    }
}
