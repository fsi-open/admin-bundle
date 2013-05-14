<?php

namespace FSi\Bundle\AdminBundle\Tests\Cotnext\Crud;

use FSi\Bundle\AdminBundle\Tests\Cotnext\AbstractContextBuilderTest;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractCrudListContextBuilderTest extends AbstractContextBuilderTest
{
    public function testBuildContext()
    {
        $element = $this->getElement();

        $element->expects($this->any())
            ->method('hasOption')
            ->with('template_crud_list')
            ->will($this->returnValue(false));

        $element->expects($this->any())
            ->method('hasDataSource')
            ->will($this->returnValue(true));

        $element->expects($this->any())
            ->method('getDataSource')
            ->will($this->returnValue($this->getMock('FSi\Component\DataSource\DataSourceInterface')));

        $element->expects($this->any())
            ->method('hasDataGrid')
            ->will($this->returnValue(true));

        $element->expects($this->any())
            ->method('getDataGrid')
            ->will($this->returnValue($this->getMock('FSi\Component\DataGrid\DataGridInterface')));

        $builder = $this->getContextBuilder($element);
        $context = $builder->buildContext();

        $this->assertNull($context->getTemplateName());
        $this->assertInstanceOf('FSi\Component\DataSource\DataSourceInterface', $context->getDataSource());
        $this->assertInstanceOf('FSi\Component\DataGrid\DataGridInterface', $context->getDataGrid());
    }

    /**
     * @expectedException \FSi\Bundle\AdminBundle\Exception\MissingDataGridException
     */
    public function testBuildContextWithoutDataGrid()
    {
        $element = $this->getElement();

        $element->expects($this->any())
            ->method('hasOption')
            ->with('template_crud_list')
            ->will($this->returnValue(false));

        $element->expects($this->any())
            ->method('hasDataGrid')
            ->will($this->returnValue(false));

        $builder = $this->getContextBuilder($element);
        $context = $builder->buildContext();
    }

    /**
     * @expectedException \FSi\Bundle\AdminBundle\Exception\MissingDataSourceException
     */
    public function testBuildContextWithoutDataSource()
    {
        $element = $this->getElement();

        $element->expects($this->any())
            ->method('hasOption')
            ->with('template_crud_list')
            ->will($this->returnValue(false));

        $element->expects($this->any())
            ->method('hasDataGrid')
            ->will($this->returnValue(true));

        $element->expects($this->any())
            ->method('getDataGrid')
            ->will($this->returnValue($this->getMock('FSi\Component\DataGrid\DataGridInterface')));

        $element->expects($this->any())
            ->method('hasDataSource')
            ->will($this->returnValue(false));

        $builder = $this->getContextBuilder($element);
        $context = $builder->buildContext();
    }
}