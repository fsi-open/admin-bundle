<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Field;

use FSi\Component\DataSource\Field\FieldView;

/**
 * Tests for FieldView.
 */
class DataSourceViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Checks creation.
     */
    public function testCreate()
    {
        $field = $this->getMock('FSi\Component\DataSource\Field\FieldTypeInterface');

        $field
            ->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('somename'))
        ;

        $field
            ->expects($this->atLeastOnce())
            ->method('getType')
            ->will($this->returnValue('sometype'))
        ;

        $field
            ->expects($this->atLeastOnce())
            ->method('getComparison')
            ->will($this->returnValue('somecomp'))
        ;

        $field
            ->expects($this->atLeastOnce())
            ->method('getCleanParameter')
            ->will($this->returnValue('someparam'))
        ;

        $fieldView = new FieldView($field);

        $this->assertEquals($field->getName(), $fieldView->getName());
        $this->assertEquals($field->getType(), $fieldView->getType());
        $this->assertEquals($field->getComparison(), $fieldView->getComparison());
        $this->assertEquals($field->getCleanParameter(), $fieldView->getParameter());
    }

    /**
     * Checks correctness of reference to DataSourceView.
     */
    public function testSetDataSourceView()
    {
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));
        $view = $this->getMock('FSi\Component\DataSource\DataSourceView', array(), array($datasource));
        $field = $this->getMock('FSi\Component\DataSource\Field\FieldTypeInterface');
        $fieldView = new FieldView($field);

        $fieldView->setDataSourceView($view);
        $this->assertEquals($fieldView->getDataSourceView(), $view);
    }

    /**
     * Checks the correctness of options related methods.
     */
    public function testOptionsManipulation()
    {
        $field = $this->getMock('FSi\Component\DataSource\Field\FieldTypeInterface');
        $view = new FieldView($field);

        $this->assertFalse($view->hasAttribute('option1'));
        $view->setAttribute('option1', 'value1');
        $this->assertTrue($view->hasAttribute('option1'));
        $this->assertEquals($view->getAttribute('option1'), 'value1');
        $view->removeAttribute('option1');
        $this->assertFalse($view->hasAttribute('option1'));

        $view->setAttribute('option2', '');
        $this->assertTrue($view->hasAttribute('option2'));

        $view->setAttribute('option2', null);
        $this->assertTrue($view->hasAttribute('option2'));

        $view->setAttribute('option3', 'value3');
        $view->setAttribute('option4', 'value4');

        $this->assertEquals(array('option2' => null, 'option3' => 'value3', 'option4' => 'value4'), $view->getAttributes());

        $this->assertEquals(null, $view->getAttribute('option5'));
    }
}
