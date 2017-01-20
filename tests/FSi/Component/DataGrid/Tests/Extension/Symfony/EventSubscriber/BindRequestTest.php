<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Symfony\EventSubscriber;

use FSi\Component\DataGrid\Extension\Symfony\EventSubscriber\BindRequest;

class BindRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testPreBindDataWithoutRequestObject()
    {
        $event = $this->getMock('FSi\Component\DataGrid\DataGridEventInterface');
        $event->expects($this->never())
            ->method('setData');

        $subscriber = new BindRequest();

        $subscriber->preBindData($event);
    }

    public function testPreBindDataPOST()
    {
        if (!class_exists('Symfony\Component\HttpFoundation\Request')) {
            $this->markTestSkipped('Symfony Column Extension require Symfony\Component\HttpFoundation\Request class.');
        }

        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->once())
             ->method('getMethod')
             ->will($this->returnValue('POST'));

        $requestBag = $this->getMock('Symfony\Component\HttpFoundation\ParameterBag');
        $requestBag->expects($this->once())
            ->method('get')
            ->with('grid', array())
            ->will($this->returnValue(array('foo' => 'bar')));

        $request->request = $requestBag;

        $grid = $this->getMock('FSi\Component\DataGrid\DataGridInterface');
        $grid->expects($this->once())
             ->method('getName')
             ->will($this->returnValue('grid'));

        $event = $this->getMock('FSi\Component\DataGrid\DataGridEventInterface');
        $event->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($request));

        $event->expects($this->once())
            ->method('setData')
            ->with(array('foo' => 'bar'));

        $event->expects($this->once())
            ->method('getDataGrid')
            ->will($this->returnValue($grid));

        $subscriber = new BindRequest();

        $subscriber->preBindData($event);
    }

    public function testPreBindDataGET()
    {
        if (!class_exists('Symfony\Component\HttpFoundation\Request')) {
            $this->markTestSkipped('Symfony Column Extension require Symfony\Component\HttpFoundation\Request class.');
        }

        $request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->once())
             ->method('getMethod')
             ->will($this->returnValue('GET'));

        $queryBag = $this->getMock('Symfony\Component\HttpFoundation\ParameterBag');
        $queryBag->expects($this->once())
            ->method('get')
            ->with('grid', array())
            ->will($this->returnValue(array('foo' => 'bar')));

        $request->query = $queryBag;

        $grid = $this->getMock('FSi\Component\DataGrid\DataGridInterface');
        $grid->expects($this->once())
             ->method('getName')
             ->will($this->returnValue('grid'));

        $event = $this->getMock('FSi\Component\DataGrid\DataGridEventInterface');
        $event->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($request));

        $event->expects($this->once())
            ->method('setData')
            ->with(array('foo' => 'bar'));

        $event->expects($this->once())
            ->method('getDataGrid')
            ->will($this->returnValue($grid));

        $subscriber = new BindRequest();

        $subscriber->preBindData($event);
    }
}
