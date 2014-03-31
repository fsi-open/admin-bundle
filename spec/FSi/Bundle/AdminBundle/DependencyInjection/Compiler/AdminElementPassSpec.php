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
    private $setters = array('setDataGridFactory', 'setDataSourceFactory', 'setFormFactory', 'setManagerRegistry');

    function let(ContainerBuilder $container, Definition $def)
    {
        $container->hasDefinition('admin.manager')->willReturn(true);
        $container->findDefinition('admin.manager')->willReturn($def);
        $container->findDefinition('datagrid.factory')->willReturn($def);
        $container->findDefinition('datasource.factory')->willReturn($def);
        $container->findDefinition('form.factory')->willReturn($def);
        $container->findDefinition('doctrine')->willReturn($def);
    }

    function it_add_elements_into_manager(
        ContainerBuilder $container,
        Definition $def,
        Definition $elmFoo
    ) {
        $container->findTaggedServiceIds('admin.element')->willReturn(array(
            'admin.foo.element' => array(array())
        ));

        $container->findDefinition('admin.foo.element')->willReturn($elmFoo);

        $elmFoo->getClass()->willReturn('FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement');

        foreach ($this->setters as $setter) {
            $elmFoo->addMethodCall($setter, Argument::containing(Argument::type('Symfony\Component\DependencyInjection\Definition')))->shouldBeCalled();
        }

        $def->addMethodCall('addElement', array($elmFoo))->shouldBeCalled();

        $this->process($container);
    }
}
