<?php


namespace AdminPanel\Symfony\AdminBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\AnnotationReader;
use AdminPanel\Symfony\AdminBundle\Annotation\Element;
use AdminPanel\Symfony\AdminBundle\Extractor\BundlePathExtractor;
use AdminPanel\Symfony\AdminBundle\Finder\AdminClassFinder;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;

class AdminAnnotatedElementPass implements CompilerPassInterface
{
    const ANNOTATION_CLASS = 'AdminPanel\\Symfony\\AdminBundle\\Annotation\\Element';

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var AdminClassFinder
     */
    private $adminClassFinder;

    /**
     * @param AnnotationReader $annotationReader
     * @param AdminClassFinder $adminClassFinder
     */
    public function __construct(AnnotationReader $annotationReader, AdminClassFinder $adminClassFinder)
    {
        $this->annotationReader = $annotationReader;
        $this->adminClassFinder = $adminClassFinder;
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $paths = $this->getBundlesAdminPaths($container);

        $annotatedAdminClassess = $this->findAnnotatedAdminClasses($paths);
        $adminElementsDefinitions = array();
        foreach ($annotatedAdminClassess as $adminClass) {
            $adminElementsDefinitions[] = $this->createAdminElementDefinition($adminClass);
        }
        $container->addDefinitions($adminElementsDefinitions);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return array
     */
    private function getBundlesAdminPaths(ContainerBuilder $container)
    {
        $bundleClasses = $container->getParameter('kernel.bundles');
        $paths = array();
        foreach ($bundleClasses as $bundleClass) {
            $bundleClassReflector = new \ReflectionClass($bundleClass);
            $bundleAdminPath = dirname($bundleClassReflector->getFileName()) . '/Admin';
            if (is_dir($bundleAdminPath)) {
                $container->addResource(new DirectoryResource($bundleAdminPath, '/\.php$/'));
                $paths[] = $bundleAdminPath;
            }
        }
        return $paths;
    }

    /**
     * @param $class
     * @return \Symfony\Component\DependencyInjection\Definition
     */
    private function createAdminElementDefinition($class)
    {
        $definition = new Definition($class);
        $definition->addTag('admin.element');

        return $definition;
    }

    /**
     * @param array $paths
     * @return array
     */
    private function findAnnotatedAdminClasses(array $paths)
    {
        $annotatedAdminClasses = array();

        foreach ($this->adminClassFinder->findClasses($paths) as $class) {
            $annotation = $this->annotationReader->getClassAnnotation(
                new \ReflectionClass($class),
                self::ANNOTATION_CLASS
            );

            if (isset($annotation) && $annotation instanceof Element) {
                $annotatedAdminClasses[] = $class;
            }
        }

        return $annotatedAdminClasses;
    }
}
