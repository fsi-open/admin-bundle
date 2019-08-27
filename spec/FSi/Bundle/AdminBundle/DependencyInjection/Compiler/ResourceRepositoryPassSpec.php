<?php

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ResourceRepositoryPassSpec extends ObjectBehavior
{
    public function it_does_nothing_when_there_is_no_resource_extension(ContainerBuilder $container)
    {
        $container->hasExtension('fsi_resource_repository')->willReturn(false);
        $this->process($container);
    }

    public function it_loads_resources_config_only_if_resource_repository_extension_exists(
        ContainerBuilder $container,
        ParameterBag $bag
    ) {
        $container->hasExtension(Argument::type('string'))->willReturn(false);
        $container->hasExtension('fsi_resource_repository')->willReturn(true);

        if (method_exists(ContainerBuilder::class, 'fileExists')) {
            $container->fileExists(Argument::that(function ($value) {
                return preg_match('/context\/resource\.xml$/', $value);
            }))->shouldBeCalled();
        } else {
            $container->addResource(Argument::allOf(
                Argument::type(FileResource::class),
                Argument::that(function($value) {
                    return $value instanceof FileResource &&
                        preg_match('/context\/resource\.xml$/', $value->getResource());
                })
            ))->shouldBeCalled();
        }

        if (true === method_exists(ContainerBuilder::class, 'removeBindings')) {
            $container->removeBindings(Argument::type('string'))->shouldBeCalled();
        }

        $container->getParameterBag()->willReturn($bag);
        $container->setDefinition(
            Argument::type('string'),
            Argument::type(Definition::class)
        )->shouldBeCalled();

        $this->process($container);
    }
}
