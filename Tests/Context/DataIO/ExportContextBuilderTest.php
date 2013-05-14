<?php

namespace FSi\Bundle\AdminBundle\Tests\Cotnext\Crud;

use FSi\Bundle\AdminBundle\Context\DataIO\ExportContextBuilder;
use FSi\Bundle\AdminBundle\Structure\ElementInterface;
use FSi\Bundle\AdminBundle\Tests\Cotnext\AbstractContextBuilderTest;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ExportContextBuilderText extends AbstractContextBuilderTest
{
    /**
     * @return ElementInterface
     */
    public function getElement()
    {
        return $this->getMock('FSi\Bundle\AdminBundle\Structure\AdminElementInterface');
    }

    /**
     * @param ElementInterface $element
     * @return ExportContextBuilder|mixed
     */
    public function getContextBuilder(ElementInterface $element)
    {
        return new ExportContextBuilder($element);
    }

    public function testBuildContext()
    {
        $element = $this->getElement();

        $element->expects($this->any())
            ->method('hasExportDataSource')
            ->will($this->returnValue(true));

        $element->expects($this->any())
            ->method('getExportDataSource')
            ->will($this->returnValue($this->getMock('FSi\Component\DataSource\DataSourceInterface')));

        $element->expects($this->any())
            ->method('hasExportDataGrid')
            ->will($this->returnValue(true));

        $element->expects($this->any())
            ->method('getExportDataGrid')
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
            ->method('hasExportDataGrid')
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
            ->method('hasExportDataGrid')
            ->will($this->returnValue(true));

        $element->expects($this->any())
            ->method('getExportDataGrid')
            ->will($this->returnValue($this->getMock('FSi\Component\DataGrid\DataGridInterface')));

        $element->expects($this->any())
            ->method('hasExportDataSource')
            ->will($this->returnValue(false));

        $builder = $this->getContextBuilder($element);
        $context = $builder->buildContext();
    }
}