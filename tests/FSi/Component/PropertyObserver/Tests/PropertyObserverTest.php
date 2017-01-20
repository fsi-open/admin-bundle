<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\PropertyObserver\Tests;

use FSi\Component\PropertyObserver\MultiplePropertyObserver;
use FSi\Component\PropertyObserver\PropertyObserver;

class PropertyObserverTest extends \PHPUnit_Framework_TestCase
{
    public function testValueChanged()
    {
        $observer = new PropertyObserver();

        $object = new TestObject();
        $object->property1 = 'original value 1';
        $object->property2 = 'original value 2';

        $observer->saveValue($object, 'property1');
        $observer->saveValue($object, 'property2');
        $observer->saveValue($object, 'property3');

        $object->property1 = 'new value 1';
        $object->property3 = 'new value 3';
        $this->assertTrue($observer->hasSavedValue($object, 'property1'));
        $this->assertTrue($observer->hasValueChanged($object, 'property1'));
        $this->assertTrue($observer->hasSavedValue($object, 'property2'));
        $this->assertFalse($observer->hasValueChanged($object, 'property2'));
        $this->assertTrue($observer->hasValueChanged($object, 'property3'));
        $this->assertTrue($observer->hasSavedValue($object, 'property3'));
        $this->assertFalse($observer->hasSavedValue($object, 'property4'));
        $this->setExpectedException('FSi\Component\PropertyObserver\Exception\BadMethodCallException');
        $observer->hasValueChanged($object, 'property4');
    }

    public function testChangedValue()
    {
        $observer = new PropertyObserver();

        $object = new TestObject();
        $object->property1 = 'original value 1';
        $object->property2 = 'original value 2';

        $observer->saveValue($object, 'property1');
        $observer->saveValue($object, 'property2');
        $observer->saveValue($object, 'property3');

        $object->property1 = 'new value 1';
        $object->property3 = 'new value 3';
        $this->assertTrue($observer->hasSavedValue($object, 'property1'));
        $this->assertTrue($observer->hasChangedValue($object, 'property1'));
        $this->assertTrue($observer->hasSavedValue($object, 'property2'));
        $this->assertFalse($observer->hasChangedValue($object, 'property2'));
        $this->assertTrue($observer->hasChangedValue($object, 'property3'));
        $this->assertTrue($observer->hasSavedValue($object, 'property3'));
        $this->assertFalse($observer->hasSavedValue($object, 'property4'));
        $this->setExpectedException('FSi\Component\PropertyObserver\Exception\BadMethodCallException');
        $observer->hasChangedValue($object, 'property4');
    }

    public function testSetValue()
    {
        $observer = new PropertyObserver();

        $object = new TestObject();
        $observer->setValue($object, 'property1', 'original value 1');
        $observer->setValue($object, 'property2', 'original value 2');
        $observer->setValue($object, 'property3', 'original value 3');

        $object->property1 = 'new value 1';
        $object->property3 = 'new value 3';
        $this->assertTrue($observer->hasChangedValue($object, 'property1'));
        $this->assertFalse($observer->hasChangedValue($object, 'property2'));
        $this->assertTrue($observer->hasChangedValue($object, 'property3'));
        $this->setExpectedException('FSi\Component\PropertyObserver\Exception\BadMethodCallException');
        $observer->hasChangedValue($object, 'property4');
    }

    public function testGetSavedValue()
    {
        $observer = new PropertyObserver();

        $object = new TestObject();
        $object->property1 = 'original value 1';
        $object->property2 = 'original value 2';

        $observer->saveValue($object, 'property1');
        $observer->saveValue($object, 'property2');
        $observer->saveValue($object, 'property3');

        $object->property1 = 'new value 1';
        $object->property3 = 'new value 3';
        $this->assertEquals(
            $observer->getSavedValue($object, 'property1'),
            'original value 1'
        );
        $this->assertEquals(
            $observer->getSavedValue($object, 'property2'),
            'original value 2'
        );
        $this->assertNull($observer->getSavedValue($object, 'property3'));
        $this->setExpectedException('FSi\Component\PropertyObserver\Exception\BadMethodCallException');
        $observer->getSavedValue($object, 'property4');
    }

    public function testSaveMultipleValues()
    {
        $observer = new MultiplePropertyObserver();
        $properties = array('property1', 'property2', 'property3');

        $object = new TestObject();
        $object->property1 = 'original value 1';
        $object->property2 = 'original value 2';

        $observer->saveValues($object, $properties);

        $this->assertEquals(
            $observer->getSavedValue($object, 'property1'),
            'original value 1'
        );
        $this->assertEquals(
            $observer->getSavedValue($object, 'property2'),
            'original value 2'
        );
        $this->assertNull(
            $observer->getSavedValue($object, 'property3')
        );

        $object->property1 = 'new value 1';
        $object->property3 = 'new value 3';

        $this->assertTrue(
            $observer->hasChangedValues($object, $properties)
        );
    }

    public function testTreatNotSavedAsNull()
    {
        $observer = new PropertyObserver();

        $object = new TestObject();
        $object->property1 = 'original value 1';
        $object->property2 = 'original value 2';

        $object->property1 = 'new value 1';
        $object->property3 = 'new value 3';
        $this->assertTrue($observer->hasChangedValue($object, 'property1', true));
        $this->assertTrue($observer->hasChangedValue($object, 'property2', true));
        $this->assertTrue($observer->hasChangedValue($object, 'property3', true));
        $this->assertFalse($observer->hasChangedValue($object, 'property4', true));
    }

    public function testRemoveObject()
    {
        $observer = new PropertyObserver();

        $object1 = new TestObject();
        $object1->property1 = 'original value 1';
        $object2 = new TestObject();
        $object2->property2 = 'original value 2';
        $observer->saveValue($object1, 'property1');
        $observer->saveValue($object2, 'property2');

        $this->assertTrue($observer->hasSavedValue($object1, 'property1'));
        $this->assertTrue($observer->hasSavedValue($object2, 'property2'));

        $observer->remove($object1);

        $this->assertFalse($observer->hasSavedValue($object1, 'property1'));
        $this->assertTrue($observer->hasSavedValue($object2, 'property2'));
    }

    public function testClear()
    {
        $observer = new PropertyObserver();

        $object1 = new TestObject();
        $object1->property1 = 'original value 1';
        $object2 = new TestObject();
        $object2->property2 = 'original value 2';
        $observer->saveValue($object1, 'property1');
        $observer->saveValue($object2, 'property2');

        $this->assertTrue($observer->hasSavedValue($object1, 'property1'));
        $this->assertTrue($observer->hasSavedValue($object2, 'property2'));

        $observer->clear();

        $this->assertFalse($observer->hasSavedValue($object1, 'property1'));
        $this->assertFalse($observer->hasSavedValue($object2, 'property2'));
    }
}

class TestObject
{
    public $property1;

    public $property2;

    public $property3;

    public $property4;
}
