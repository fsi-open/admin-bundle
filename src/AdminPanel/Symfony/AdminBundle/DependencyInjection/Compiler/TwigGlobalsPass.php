<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigGlobalsPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('twig')) {
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
