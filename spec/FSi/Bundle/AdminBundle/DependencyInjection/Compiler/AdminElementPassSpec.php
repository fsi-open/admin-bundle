<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AdminElementPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\DependencyInjection\Compiler\AdminElementPass');
    }

    function it_add_elements_into_manager(ContainerBuilder $container, Definition $def, Definition $elmFoo, Definition $elmBar)
    {
        $container->hasDefinition('admin.manager')->shouldBeCalled()->willReturn(true);
        $container->findDefinition('admin.manager')->willReturn($def);
        $container->findTaggedServiceIds('admin.element')->shouldBeCalled()->willReturn(array(
            'admin.foo.element' => array(array()),
            'admin.bar.element' => array(array('alias' => 'bar.group'))
        ));
        $container->findDefinition('admin.foo.element')->willReturn($elmFoo);
        $container->findDefinition('admin.bar.element')->willReturn($elmBar);

        $container->findDefinition('datagrid.factory')->willReturn($def);
        $container->findDefinition('datasource.factory')->willReturn($def);
        $container->findDefinition('form.factory')->willReturn($def);
        $container->findDefinition('doctrine')->willReturn($def);

        $elmFoo->getClass()->willReturn('FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement');
        $elmBar->getClass()->willReturn('FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement');

        $elmFoo->addMethodCall('setDataGridFactory', Argument::containing(Argument::type('Symfony\Component\DependencyInjection\Definition')))
            ->shouldBeCalled();
        $elmFoo->addMethodCall('setDataSourceFactory', Argument::containing(Argument::type('Symfony\Component\DependencyInjection\Definition')))
            ->shouldBeCalled();
        $elmFoo->addMethodCall('setFormFactory', Argument::containing(Argument::type('Symfony\Component\DependencyInjection\Definition')))
            ->shouldBeCalled();
        $elmFoo->addMethodCall('setManagerRegistry', Argument::containing(Argument::type('Symfony\Component\DependencyInjection\Definition')))
            ->shouldBeCalled();

        $elmBar->addMethodCall('setDataGridFactory', Argument::containing(Argument::type('Symfony\Component\DependencyInjection\Definition')))
            ->shouldBeCalled();
        $elmBar->addMethodCall('setDataSourceFactory', Argument::containing(Argument::type('Symfony\Component\DependencyInjection\Definition')))
            ->shouldBeCalled();
        $elmBar->addMethodCall('setFormFactory', Argument::containing(Argument::type('Symfony\Component\DependencyInjection\Definition')))
            ->shouldBeCalled();
        $elmBar->addMethodCall('setManagerRegistry', Argument::containing(Argument::type('Symfony\Component\DependencyInjection\Definition')))
            ->shouldBeCalled();


        $def->addMethodCall('addElement', array(
            $elmFoo,
            null
        ))->shouldBeCalled();

        $def->addMethodCall('addElement', array(
            $elmBar,
            'bar.group'
        ))->shouldBeCalled();

        $this->process($container);
    }
}
