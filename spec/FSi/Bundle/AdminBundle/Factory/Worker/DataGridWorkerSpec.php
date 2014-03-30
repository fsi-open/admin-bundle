<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Component\DataGrid\DataGridFactory;
use PhpSpec\ObjectBehavior;

class DataGridWorkerSpec extends ObjectBehavior
{
    function let(DataGridFactory $dataGridFactory)
    {
        $this->beConstructedWith($dataGridFactory);
    }

    function it_mount_datagrid_factory_to_elements_that_are_datagrid_aware(AbstractCRUD $element, DataGridFactory $dataGridFactory)
    {
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
