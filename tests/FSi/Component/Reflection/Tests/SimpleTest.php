<?php

namespace FSi\Component\Reflection\Tests;

use FSi\Component\Reflection\ReflectionClass;
use FSi\Component\Reflection\ReflectionProperty;
use FSi\Component\Reflection\ReflectionMethod;
use FSi\Component\Reflection\Tests\Fixture\ClassA;

class SampleTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $classReflection1 = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassA');
        $classReflection2 = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassA');
        $this->assertSame($classReflection1, $classReflection2);

        $classReflection3 = $classReflection2->getMethod('privateMethod')->getDeclaringClass();
        $this->assertSame($classReflection2, $classReflection3);

        $classReflection4 = ReflectionClass::factory(new ClassA('param'));
        $this->assertSame($classReflection1, $classReflection4);

        $classReflection5 = $classReflection2->getProperty('privateProperty')->getDeclaringClass();
        $this->assertSame($classReflection2, $classReflection5);

        $obj = new ClassA('param');
        $classReflection6 = ReflectionClass::factory($obj);
        $this->assertSame($classReflection2, $classReflection6);
    }
     
    public function testClassGetProperties()
    {
        $filters = array(
            ReflectionProperty::IS_STATIC, 
            ReflectionProperty::IS_PUBLIC , 
            ReflectionProperty::IS_PROTECTED, 
            ReflectionProperty::IS_PRIVATE
        );
        
        $filtersCombinations = $this->pc_array_power_set($filters);
        
        foreach ($filtersCombinations as $combination) {
            $filter = null;
            foreach ($combination as $value) {
                if (!isset($filter)) {
                    $filter = $value;
                } else {
                    $filter = $filter | $value;
                }
            }
            $this->_testClassGetProperties($filter);
        }
    }
    
    protected function _testClassGetProperties($filter = null)
    {
        $fsiClassReflection = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassA');
        $classReflection    = new \ReflectionClass('FSi\Component\Reflection\Tests\Fixture\ClassA');

        $fsiReflectionProperties  = isset($filter) ? $fsiClassReflection->getProperties($filter) : $fsiClassReflection->getProperties();
        $reflectionProperties     = isset($filter) ? $classReflection->getProperties($filter) : $classReflection->getProperties();

        $this->assertSame(count($fsiReflectionProperties), count($reflectionProperties));

        foreach ($fsiReflectionProperties as $index => $reflectionProperty) {
            $reflectionPropertyNew = ReflectionProperty::factory($reflectionProperty->class, $reflectionProperty->name);
            $this->assertSame($reflectionPropertyNew, $reflectionProperty);

            $orgReflectionProperty = $reflectionProperties[$index];
            
            $this->assertSame($orgReflectionProperty->name, $reflectionProperty->name);
            $this->assertSame($orgReflectionProperty->class, $reflectionProperty->class);
        }
    }

    public function testClassGetMethods()
    {
        $filters = array(
            ReflectionMethod::IS_STATIC, 
            ReflectionMethod::IS_PUBLIC , 
            ReflectionMethod::IS_PROTECTED, 
            ReflectionMethod::IS_PRIVATE,
            ReflectionMethod::IS_ABSTRACT,
            ReflectionMethod::IS_FINAL
        );
        
        $filtersCombinations = $this->pc_array_power_set($filters);
        
        foreach ($filtersCombinations as $combination) {
            $filter = null;
            foreach ($combination as $value) {
                if (!isset($filter)) {
                    $filter = $value;
                } else {
                    $filter = $filter | $value;
                }
            }
            $this->_testClassGetMethods($filter);
        }
    }
    
    protected function _testClassGetMethods($filter = null)
    {
        $fsiClassReflection = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassA');
        $classReflection    = new \ReflectionClass('FSi\Component\Reflection\Tests\Fixture\ClassA');
        
        $fsiReflectionMethods  = isset($filter) ? $fsiClassReflection->getMethods($filter) : $fsiClassReflection->getMethods();
        $reflectionMethods     = isset($filter) ? $classReflection->getMethods($filter) : $classReflection->getMethods();  
        
        $this->assertSame(count($fsiReflectionMethods), count($reflectionMethods));
        
        foreach ($fsiReflectionMethods as $index => $reflectionMethod) {
            
            $reflectionMethodNew = ReflectionMethod::factory($reflectionMethod->class, $reflectionMethod->name);
            $this->assertSame($reflectionMethodNew, $reflectionMethod);

            $orgReflectionMethod = $reflectionMethods[$index];
            
            $this->assertSame($orgReflectionMethod->name, $reflectionMethod->name);
            $this->assertSame($orgReflectionMethod->class, $reflectionMethod->class);
        }
    }
    
    public function testMethod()
    {
        $methodReflection1 = ReflectionMethod::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'protectedMethod');
        $methodReflection2 = ReflectionMethod::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'protectedMethod');
        $this->assertSame($methodReflection1, $methodReflection2);

        $methodReflection3 = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassA')->getMethod('protectedMethod');
        $this->assertSame($methodReflection1, $methodReflection3);

        $obj = new ClassA('param');
        $methodReflection4 = ReflectionMethod::factory($obj, 'protectedMethod');
        $this->assertSame($methodReflection1, $methodReflection4);

        $res = $methodReflection1->invoke($obj, 'foo', 'bar');
        $this->assertEquals($res, 'foo+bar');

        $methodReflection5 = ReflectionMethod::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'privateMethod');
        $res = $methodReflection5->invoke($obj, 'foo', 'bar');
        $this->assertEquals($res, 'foo-bar');

        $methodReflection6 = ReflectionMethod::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'publicMethod');
        $res = $methodReflection6->invoke($obj, 'foo', 'bar');
        $this->assertEquals($res, 'foo=bar');
    }

    public function testInvalidMethod()
    {
        $this->setExpectedException('ReflectionException');
        $methodReflection = ReflectionMethod::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'invalidMethod');
    }
       
    public function testProperty()
    {
        $propertyReflection1 = ReflectionProperty::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'protectedProperty');
        $propertyReflection2 = ReflectionProperty::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'protectedProperty');
        $this->assertSame($propertyReflection1, $propertyReflection2);

        $propertyReflection3 = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassA')->getProperty('protectedProperty');
        $this->assertSame($propertyReflection1, $propertyReflection3);

        $obj = new ClassA('param');
        $propertyReflection4 = ReflectionProperty::factory($obj, 'protectedProperty');
        $this->assertSame($propertyReflection1, $propertyReflection4);

        $propertyReflection1->setValue($obj, 'foo');
        $this->assertAttributeEquals('foo', 'protectedProperty', $obj);
        $this->assertEquals('foo', $propertyReflection1->getValue($obj));

        $propertyReflection5 = ReflectionProperty::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'privateProperty');
        $propertyReflection5->setValue($obj, 'bar');
        $this->assertAttributeEquals('bar', 'privateProperty', $obj);
        $this->assertEquals('bar', $propertyReflection5->getValue($obj));

        $propertyReflection6 = ReflectionProperty::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'publicProperty');
        $propertyReflection6->setValue($obj, 'baz');
        $this->assertAttributeEquals('baz', 'publicProperty', $obj);
        $this->assertEquals('baz', $propertyReflection6->getValue($obj));
    }

    public function testInvalidProperty()
    {
        $this->setExpectedException('ReflectionException');
        $propertyReflection = ReflectionProperty::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'invalidProperty');
    }
    
    public function testExceptionClass()
    {
        $this->setExpectedException('ReflectionException');
        $reflectionClass = new ReflectionClass('FSi\Component\Reflection\Tests\Fixture\ClassA');
    }

    public function testExceptionProperty()
    {
        $this->setExpectedException('ReflectionException');
        $reflectionProperty = new ReflectionProperty('FSi\Component\Reflection\Tests\Fixture\ClassA', 'protectedProperty');
    }

    public function testExceptionMethod()
    {
        $this->setExpectedException('ReflectionException');
        $reflectionMethod = new ReflectionMethod('FSi\Component\Reflection\Tests\Fixture\ClassA', 'protectedMethod');
    }
    
    public function testClassInterfaces()
    {
        $classFsiReflection = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassA');
        $classReflection    = new \ReflectionClass('FSi\Component\Reflection\Tests\Fixture\ClassA');
        
        $fsiClassInterfaces = $classFsiReflection->getInterfaces();
        $classInterfaces    = $classReflection->getInterfaces();
        
        $this->assertSame(count($fsiClassInterfaces), count($classInterfaces));
        
        foreach ($fsiClassInterfaces as $name => $interfaceReflection) {
            $orgInterface = $classInterfaces[$name];
            $this->assertEquals($orgInterface->name, $interfaceReflection->name);
        }

    }
    
    public function testGetParentClassPropertiesAndMethods()
    {
        $publicProperty3     = ReflectionProperty::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'publicProperty3');
        $ClassAParent  = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassAParent');
        $ClassAParentParent  = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassAParentParent');
        
        $ClassAParentProperties        = $ClassAParent->getProperties();
        $ClassAParentParentProperties  = $ClassAParentParent->getProperties();
        
        $propertyExists = false;
        foreach ($ClassAParentProperties as $index => $parentProperty) {
            if ($parentProperty->name == $publicProperty3->name) {
                $this->assertSame($parentProperty, $publicProperty3);
                $propertyExists = true;
            }
        }
        $this->assertTrue($propertyExists);
        
        $propertyExists = false;
        foreach ($ClassAParentParentProperties as $index => $parentParentProperty) {
            if ($parentParentProperty->name == $publicProperty3->name) {
                $this->assertSame($parentParentProperty, $publicProperty3);
                $propertyExists = true;
            }
        }
        $this->assertTrue($propertyExists);
        
        
        
        $publicMethod3     = ReflectionMethod::factory('FSi\Component\Reflection\Tests\Fixture\ClassA', 'publicMethod3');
        $ClassAParent      = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassAParent');
        $ClassAParentParent  = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassAParentParent');
        
        $ClassAParentMethods       = $ClassAParent->getMethods();
        $ClassAParentParentMethods = $ClassAParentParent->getMethods();
        
        $methodExists = false;
        foreach ($ClassAParentMethods as $index => $parentMethod) {
            if ($parentMethod->name == $publicMethod3->name) {
                $this->assertSame($parentMethod, $publicMethod3);
                $methodExists = true;
            }
        }       
        $this->assertTrue($methodExists);
        
        $methodExists = false;
        foreach ($ClassAParentParentMethods as $index => $parentParentMethod) {
            if ($parentParentMethod->name == $publicMethod3->name) {
                $this->assertSame($parentParentMethod, $publicMethod3);
                $methodExists = true;
            }
        }
        $this->assertTrue($methodExists);
    }
    
    public function testGetParentClass()
    {
        $classFsiReflection = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassAParentParent');
        $parentClassFsiReflection = $classFsiReflection->getParentClass();
        
        $classReflection = new \ReflectionClass('FSi\Component\Reflection\Tests\Fixture\ClassAParentParent');
        $parentClassReflection = $classReflection->getParentClass();

        $this->assertSame($parentClassFsiReflection, $parentClassReflection);
        
        
        $classFsiReflection1 = ReflectionClass::factory('FSi\Component\Reflection\Tests\Fixture\ClassA');
        $parentClassFsiReflection1 = $classFsiReflection1->getParentClass();
        
        $classReflection1 = new \ReflectionClass('FSi\Component\Reflection\Tests\Fixture\ClassA');
        $parentClassReflection1 = $classReflection1->getParentClass();
        
        $this->assertSame($parentClassFsiReflection1->name, $parentClassReflection1->name);
    }
    
    protected function pc_array_power_set($array) {
        $results = array(array( ));
        foreach ($array as $element)
            foreach ($results as $combination)
                array_push($results, array_merge(array($element), $combination));
    
        return $results;
    }
}
