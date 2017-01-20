<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use FSi\Component\DataSource\DataSourceView;

/**
 * Tests for DataSourceView.
 */
class DataSourceViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Checks creation of view.
     */
    public function testViewCreate()
    {
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));
        $datasource
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('ds'))
        ;

        $view = new DataSourceView($datasource);
        $this->assertEquals($view->getName(), 'ds');
    }

    /**
     * Checks if view properly proxy some requests to datasource.
     */
    public function testGetParameters()
    {
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));

        $datasource
            ->expects($this->once())
            ->method('getParameters')
            ->will($this->returnValue(array('datasource' => array())))
        ;

        $datasource
            ->expects($this->once())
            ->method('getOtherParameters')
            ->will($this->returnValue(array('other_datasource' => array())))
        ;

        $view = new DataSourceView($datasource);
        $view->getParameters();
        $view->getOtherParameters();
        $allParameters = $view->getAllParameters();

        $this->assertEquals(
            $allParameters,
            array(
                'datasource' => array(),
                'other_datasource' => array()
            )
        );
    }

    /**
     * Checks if view properly proxy some requests to datasource.
     */
    public function testGetResults()
    {
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));

        $datasource
            ->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(new ArrayCollection()));

        $view = new DataSourceView($datasource);
        $view->getParameters();

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $view->getResult());
    }

    /**
     * Checks the correctness of options related methods.
     */
    public function testOptionsManipulation()
    {
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));
        $view = new DataSourceView($datasource);

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

        $this->assertEquals($view->getAttributes(), array('option2' => null, 'option3' => 'value3', 'option4' => 'value4'));

        $this->assertEquals(null, $view->getAttribute('option2'));
    }

    /**
     * Checks the correctness of field related methods.
     */
    public function testFieldsManipulation()
    {
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));
        $fieldView1 = $this->getMock('FSi\Component\DataSource\Field\FieldViewInterface');
        $fieldView2 = $this->getMock('FSi\Component\DataSource\Field\FieldViewInterface');
        $view = new DataSourceView($datasource);

        $fieldView1
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('name1'))
        ;

        $fieldView2
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('name2'))
        ;

        $view->addField($fieldView1);
        $view->addField($fieldView2);

        $this->assertEquals(count($view->getFields()), 2);
        $this->assertTrue($view->hasField('name1'));
        $this->assertTrue($view->hasField('name2'));
        $this->assertFalse($view->hasField('wrong'));

        //Should be no exception throwed.
        $view->getField('name1');
        $view->getField('name2');

        $this->setExpectedException('FSi\Component\DataSource\Exception\DataSourceViewException');
        $view->addField($fieldView1);
    }

    /**
     * Checks exception throwed when adding another field with the same name.
     */
    public function testAddFieldException2()
    {
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));
        $fieldView = $this->getMock('FSi\Component\DataSource\Field\FieldViewInterface');
        $view = new DataSourceView($datasource);

        $fieldView
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('name'))
        ;

        $view->addField($fieldView);

        $view->getField('name');
        $this->setExpectedException('FSi\Component\DataSource\Exception\DataSourceViewException');
        $view->getField('wrong');
    }

    /**
     * Checks implementation of \Countable, \SeekableIterator and \ArrayAccess interface.
     */
    public function testInterfaces()
    {
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));

        $fielsViews = array();
        for ($x = 0; $x < 5; $x++) {
            $fieldView = $this->getMock('FSi\Component\DataSource\Field\FieldViewInterface');

            $fieldView
                ->expects($this->any())
                ->method('getName')
                ->will($this->returnValue("name$x"))
            ;

            $fieldsViews[] = $fieldView;
        }

        $view = new DataSourceView($datasource);
        $view->addField($fieldsViews[0]);

        $this->assertEquals(1, count($view));
        $this->assertTrue(isset($view['name0']));
        $this->assertFalse(isset($view['name1']));

        foreach ($view as $key => $value) {
            $this->assertEquals('name0', $key);
        }

        $view->addField($fieldsViews[1]);
        $view->addField($fieldsViews[2]);

        $this->assertEquals('name0', $view->key());
        $view->next();
        $this->assertEquals('name1', $view->key());

        $view->addField($fieldsViews[3]);
        $view->addField($fieldsViews[4]);

        //After adding fields iterator resets on its own.
        $this->assertEquals('name0', $view->key());

        $this->assertEquals(5, count($view));
        $this->assertTrue(isset($view['name1']));

        $view->seek(1);
        $this->assertEquals('name1', $view->current()->getName());
        $this->assertEquals('name1', $view->key());

        $fields = array();
        for ($view->rewind(); $view->valid(); $view->next()) {
            $fields[] = $view->current()->getName();
        }

        $expected = array('name0', 'name1', 'name2', 'name3', 'name4');
        $this->assertEquals($expected, $fields);

        $this->assertEquals('name3', $view['name3']->getName());

        //Checking fake methods.
        $view['name0'] = 'trash';
        $this->assertNotEquals('trash', $view['name0']);
        unset($view['name0']);
        $this->assertTrue(isset($view['name0']));
    }
}
