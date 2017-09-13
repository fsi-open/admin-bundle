<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class KnpMenuBuilderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('admin.menu.knp.decorator.chain')) {
            return;
        }

        $decoratorServices = $container->findTaggedServiceIds('admin.menu.knp_decorator');

        $decorators = [];
        foreach (array_keys($decoratorServices) as $id) {
            $decorators[] = $container->findDefinition($id);
        }

        $container->findDefinition('admin.menu.knp.decorator.chain')->replaceArgument(0, $decorators);
    }
}
