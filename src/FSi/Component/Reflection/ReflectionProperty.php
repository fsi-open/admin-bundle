<?php

namespace FSi\Component\Reflection;

class ReflectionProperty extends \ReflectionProperty
{
    protected static $properties = array();

    /**
     * Constructs a new ReflectionProperty object.
     * 
     * @param string|object $class
     * @param string $name
     * @throws ReflectionException
     * @return ReflectionProperty
     */
    final public function __construct($class, $name)
    {
        $bt = debug_backtrace();
        if (!isset($bt[1]['class']) || ($bt[1]['class'] !== __CLASS__))
            throw new ReflectionException('ReflectionClass\' constructor cannot be called from outside the class');
        parent::__construct($class, $name);
    }
    
    /**
     * Constructs a new ReflectionProperty object from property and class name.
     * If object already exists in cache it will taken from there instead of creating
     * new object
     * 
     * @param string|object $class
     * @param string $property
     * @return ReflectionProperty
     */
    public static function factory($class, $property = null)
    {
        if (is_object($class))
            $class = get_class($class);
        $class = (string)$class;
        if (!isset(self::$properties[$class])) {
            $classReflection = new \ReflectionClass($class);
            $properties = $classReflection->getProperties();
            self::$properties[$class] = array();
            foreach ($properties as $propertyReflection) {
                if ($propertyReflection->class != $class) {
                    self::$properties[$class][$propertyReflection->name] = self::factory($propertyReflection->class, $propertyReflection->name);
                } else {
                    self::$properties[$class][$propertyReflection->name] = new self($propertyReflection->class, $propertyReflection->name);
                    self::$properties[$class][$propertyReflection->name]->setAccessible(true);
                }
            }
        }
        if (isset($property)) {
            if (!isset(self::$properties[$class][$property])) {
                self::$properties[$class][$property] = new self($class, $property);
                self::$properties[$class][$property]->setAccessible(true);
            }
            return self::$properties[$class][$property];
        } else
            return array_values(self::$properties[$class]);
    }

    /**
     * Get class ReflectionObject using ReflectionClass::factory method. 
     * 
     * @return ReflectionClass
     */
    public function getDeclaringClass()
    {
        return ReflectionClass::factory($this->class);
    }
}
