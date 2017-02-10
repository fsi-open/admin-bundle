<?php

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Config\Resource\FileResource;

class ResourceRepositoryPassSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function it_does_nothing_when_there_is_no_resource_extension($container)
    {
        $container->hasExtension('fsi_resource_repository')->willReturn(false);
        $this->process($container);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag $bag
     */
    public function it_loads_resources_config_only_if_resource_repository_extension_exists($container, $bag)
    {
        $container->hasExtension(Argument::type('string'))->willReturn(false);
        $container->hasExtension('fsi_resource_repository')->willReturn(true);

        $container->addResource(Argument::allOf(
            Argument::type('Symfony\Component\Config\Resource\FileResource'),
            Argument::that(function($value) {
                return $value instanceof FileResource &&
                    preg_match('/context\/resource\.xml$/', $value->getResource());
            })
        ))->shouldBeCalled();

        $container->getParameterBag()->willReturn($bag);
        $container->setDefinition(
            Argument::type('string'),
            Argument::type('Symfony\Component\DependencyInjection\Definition')
        )->shouldBeCalled();
        $container->setAlias(
            Argument::type('string'),
            Argument::type('Symfony\Component\DependencyInjection\Alias')
        )->shouldBeCalled();

        $this->process($container);
    }
}
