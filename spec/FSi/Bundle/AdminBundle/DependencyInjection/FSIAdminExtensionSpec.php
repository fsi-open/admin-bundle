<?php

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FSIAdminExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\DependencyInjection\FSIAdminExtension');
    }

    function it_set_parameters_at_container_builder(ContainerBuilder $builder,ParameterBagInterface $parameterBag,
                                                    Definition $uploadable)
    {
        $builder->hasExtension(Argument::type('string'))->willReturn(false);
        $builder->addResource(Argument::type('\Symfony\Component\Config\Resource\FileResource'))->shouldBeCalled();
        $builder->setDefinition(Argument::type('string'), Argument::type('Symfony\Component\DependencyInjection\Definition'))->shouldBeCalled();
        $builder->getParameterBag()->shouldBeCalled()->willReturn($parameterBag);
        /* Above code is added only because builder is used in services loader */

        $builder->setParameter('admin.templates.base', '@FSiAdmin/base.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.index_page', '@FSiAdmin/Admin/index.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.admin_navigationtop', '@FSiAdmin/Admin/navigationtop.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.admin_navigationleft', '@FSiAdmin/Admin/navigationleft.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.crud_list', '@FSiAdmin/CRUD/list.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.crud_create', '@FSiAdmin/CRUD/create.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.crud_edit', '@FSiAdmin/CRUD/edit.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.crud_delete', '@FSiAdmin/CRUD/delete.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.datagrid_theme', '@FSiAdmin/CRUD/datagrid.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.datasource_theme', '@FSiAdmin/CRUD/datasource.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.edit_form_theme', '@FSiAdmin/CRUD/form.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.create_form_theme', '@FSiAdmin/CRUD/form.html.twig')->shouldBeCalled();
        $builder->setParameter('admin.templates.delete_form_theme', '@FSiAdmin/CRUD/form.html.twig')->shouldBeCalled();

        $this->load(array(), $builder);
    }
}
