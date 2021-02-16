<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\AnnotationReader;
use FSi\Bundle\AdminBundle\Annotation\Element;
use FSi\Bundle\AdminBundle\Finder\AdminClassFinder;
use ReflectionClass;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @deprecated since 3.0
 */
class AdminAnnotatedElementPass implements CompilerPassInterface
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var AdminClassFinder
     */
    private $adminClassFinder;

    public function __construct(AnnotationReader $annotationReader, AdminClassFinder $adminClassFinder)
    {
        $this->annotationReader = $annotationReader;
        $this->adminClassFinder = $adminClassFinder;
    }

    public function process(ContainerBuilder $container): void
    {
        $paths = $this->getBundlesAdminPaths($container);

        $annotatedAdminClasses = $this->findAnnotatedAdminClasses($paths);
        $adminElementsDefinitions = [];
        foreach ($annotatedAdminClasses as $adminClass) {
            $adminElementsDefinitions[$adminClass] = $this->createAdminElementDefinition($adminClass);
        }

        $container->addDefinitions($adminElementsDefinitions);
    }

    private function getBundlesAdminPaths(ContainerBuilder $container): array
    {
        $bundleClasses = $container->getParameter('kernel.bundles');
        $paths = [];
        foreach ($bundleClasses as $bundleClass) {
            $bundleClassReflector = new ReflectionClass($bundleClass);
            $bundleAdminPath = dirname($bundleClassReflector->getFileName()) . '/Admin';
            if (true === is_dir($bundleAdminPath)) {
                $container->addResource(new DirectoryResource($bundleAdminPath, '/\.php$/'));
                $paths[] = $bundleAdminPath;
            }
        }
        return $paths;
    }

    private function createAdminElementDefinition(string $class): Definition
    {
        $definition = new Definition($class);
        $definition->addTag('admin.element');

        return $definition;
    }

    private function findAnnotatedAdminClasses(array $paths): array
    {
        $annotatedAdminClasses = [];

        foreach ($this->adminClassFinder->findClasses($paths) as $class) {
            $annotation = $this->annotationReader->getClassAnnotation(
                new ReflectionClass($class),
                Element::class
            );

            if (true === $annotation instanceof Element) {
                $annotatedAdminClasses[] = $class;
            }
        }

        return $annotatedAdminClasses;
    }
}
