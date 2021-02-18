<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\Environment;

class TwigGlobalsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition('twig')) {
            return;
        }

        $parameters = [
            'admin_templates_base' => $container->getParameter('admin.templates.base'),
            'admin_templates_form_theme' => $container->getParameter('admin.templates.form_theme'),
            'admin_templates_datagrid_theme' => $container->getParameter('admin.templates.datagrid_theme'),
            'admin_templates_datasource_theme' => $container->getParameter('admin.templates.datasource_theme'),
        ];

        $twig = $container->findDefinition('twig');
        foreach ($parameters as $name => $parameter) {
            $twig->addMethodCall('addGlobal', [$name, $parameter]);
        }
    }
}
