<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FSIAdminExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\DependencyInjection\FSIAdminExtension');
    }

    function it_set_parameters_at_container_builder(
        ContainerBuilder $builder,
        ParameterBagInterface $parameterBag
    ) {
        $builder->hasExtension(Argument::type('string'))->willReturn(false);
        $builder->addResource(Argument::type('\Symfony\Component\Config\Resource\FileResource'))->shouldBeCalled();
        $builder->setDefinition(Argument::type('string'), Argument::type('Symfony\Component\DependencyInjection\Definition'))->shouldBeCalled();
        $builder->getParameterBag()->shouldBeCalled()->willReturn($parameterBag);
        /* Above code is added only because builder is used in services loader */

        $builder->setParameter('admin.display_language_switch', false)->shouldBeCalled();
        $builder->setParameter('admin.templates.base', '@FSiAdmin/base.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.index_page', '@FSiAdmin/Admin/index.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.crud_list', '@FSiAdmin/CRUD/list.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.crud_create', '@FSiAdmin/CRUD/create.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.crud_edit', '@FSiAdmin/CRUD/edit.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.crud_delete', '@FSiAdmin/CRUD/delete.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.resource', '@FSiAdmin/Resource/resource.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.datagrid_theme', '@FSiAdmin/CRUD/datagrid.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.datasource_theme', '@FSiAdmin/CRUD/datasource.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.edit_form_theme', '@FSiAdmin/Form/form_div_layout.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.create_form_theme', '@FSiAdmin/Form/form_div_layout.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.delete_form_theme', '@FSiAdmin/Form/form_div_layout.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.resource_form_theme', '@FSiAdmin/Form/form_div_layout.html.twig')->shouldBeCalled();

        $builder->setDefinition(
            'admin.locale_listener',
            Argument::type('Symfony\Component\DependencyInjection\Definition')
        )->shouldNotBeCalled();

        $this->load(array(), $builder);
    }

    function it_load_locale_listener_service_definition_when_display_language_switch_is_set_to_true(
        ContainerBuilder $builder,
        ParameterBagInterface $parameterBag
    ) {
        $builder->hasExtension(Argument::type('string'))->willReturn(false);
        $builder->addResource(Argument::type('\Symfony\Component\Config\Resource\FileResource'))->shouldBeCalled();
        $builder->setDefinition(
            Argument::type('string'),
            Argument::type('Symfony\Component\DependencyInjection\Definition')
        )->shouldBeCalled();
        $builder->getParameterBag()->shouldBeCalled()->willReturn($parameterBag);

        $builder->setParameter(Argument::type('string'), Argument::type('string'))->shouldBeCalled();

        $builder->setParameter('admin.display_language_switch', Argument::type('bool'))->shouldBeCalled();

        $builder->setDefinition(
            'admin.locale_listener',
            Argument::type('Symfony\Component\DependencyInjection\Definition')
        )->shouldBeCalled();

        $this->load(array('fsi_admin' => array('display_language_switch' => true)), $builder);
    }
}
