<?php

namespace spec\FSi\Bundle\AdminBundle\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataSource\DataSourceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactory;

class ElementFactorySpec extends ObjectBehavior
{
    function let(DataGridFactory $dataGridFactory, DataSourceFactory $dataSourceFactory, FormFactory $formFactory, ManagerRegistry $managerRegistry)
    {
        $this->setDataGridFactory($dataGridFactory);
        $this->setDataSourceFactory($dataSourceFactory);
        $this->setFormFactory($formFactory);
        $this->setManagerRegistry($managerRegistry);
    }
    function it_create_admin_element()
    {
        $this->create("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\SimpleAdminElement")
            ->shouldReturnAnInstanceOf("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\SimpleAdminElement");
    }

    function it_throw_exception_when_class_does_not_implement_admin_element_interface()
    {
        $this->shouldThrow(new \InvalidArgumentException("StdClass does not seems to be an admin element."))
            ->during('create', array('StdClass'));
    }

    function it_create_admin_element_that_is_datagrid_aware()
    {
        $this->create("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\CRUDElement")
            ->shouldBeDataGridAware();
    }

    function it_create_admin_element_that_is_datasource_aware()
    {
        $this->create("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\CRUDElement")
            ->shouldBeDataSourceAware();
    }

    function it_create_admin_element_that_is_form_aware()
    {
        $this->create("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\CRUDElement")
            ->shouldBeFormAware();
    }

    function it_create_admin_element_that_is_doctrine_aware()
    {
        $this->create("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DoctrineElement")
            ->shouldBeDoctrineAware();
    }
}
