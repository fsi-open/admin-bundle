<?php

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\AnnotationReader;
use FSi\Bundle\AdminBundle\Finder\AdminClassFinder;
use FSi\Bundle\AdminBundle\Annotation\Element;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use FSi\Bundle\AdminBundle\spec\fixtures\MyBundle;
use FSi\Bundle\AdminBundle\FSiAdminBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use FSi\Bundle\AdminBundle\spec\fixtures\Admin\SimpleAdminElement;
use FSi\Bundle\AdminBundle\spec\fixtures\Admin\CRUDElement;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\DependencyInjection\Definition;

class AdminAnnotatedElementPassSpec extends ObjectBehavior
{
    function let(AnnotationReader $annotationReader, AdminClassFinder $adminClassFinder)
    {
        $this->beConstructedWith($annotationReader, $adminClassFinder);
    }

    function it_registers_annotated_admin_classes_as_services(
        ContainerBuilder $container,
        AnnotationReader $annotationReader,
        AdminClassFinder $adminClassFinder
    ) {
        $container->getParameter('kernel.bundles')->willReturn([
            MyBundle::class,
            FSiAdminBundle::class,
            FrameworkBundle::class
        ]);

        $baseDir = __DIR__ . '/../../../../../..';
        $adminClassFinder->findClasses([
            realpath($baseDir . '/spec/fixtures/Admin'),
            realpath($baseDir . '/Admin')
        ])->willReturn([
            SimpleAdminElement::class,
            CRUDElement::class
        ]);

        $annotationReader->getClassAnnotation(
            Argument::allOf(
                Argument::type('ReflectionClass'),
                Argument::which('getName', CRUDElement::class)
            ),
            Element::class
        )->willReturn(null);

        $annotationReader->getClassAnnotation(
            Argument::allOf(
                Argument::type('ReflectionClass'),
                Argument::which('getName', SimpleAdminElement::class)
            ),
            Element::class
        )->willReturn(new Element([]));

        $container->addResource(Argument::allOf(
            Argument::type(DirectoryResource::class),
            Argument::which('getResource', realpath($baseDir . '/spec/fixtures/Admin')),
            Argument::which('getPattern', '/\.php$/')
        ))->shouldBeCalled();

        $container->addResource(Argument::allOf(
            Argument::type(DirectoryResource::class),
            Argument::which('getResource', realpath($baseDir . '/Admin')),
            Argument::which('getPattern', '/\.php$/')
        ))->shouldBeCalled();

        $container->addDefinitions(Argument::that(function ($definitions) {
            if (count($definitions) !== 1) {
                return false;
            }

            /** @var Definition $definition */
            $definition = $definitions[0];
            if ($definition->getClass() !== SimpleAdminElement::class) {
                return false;
            }

            if (!$definition->hasTag('admin.element')) {
                return false;
            };

            return true;
        }))->shouldBeCalled();

        $this->process($container);
    }
}
