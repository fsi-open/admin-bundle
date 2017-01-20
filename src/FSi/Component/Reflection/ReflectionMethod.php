<?php

namespace FSi\Component\Reflection;

class ReflectionMethod extends \ReflectionMethod
{
    protected static $methods = array();

    /**
     * Constructs a new ReflectionMethod object.
     * 
     * @param string|object $class
     * @param string $name
     * @throws ReflectionException
     * @return ReflectionMethod
     */
    final public function __construct($class, $name)
    {
        $bt = debug_backtrace();
        if (!isset($bt[1]['class']) || ($bt[1]['class'] !== __CLASS__))
            throw new ReflectionException('ReflectionClass\' constructor cannot be called from outside the class');
        parent::__construct($class, $name);
    }

    /**
     * Constructs a new ReflectionMethod object from method and class name.
     * If object already exists in cache it will taken from there instead of creating
     * new object
     * 
     * @param string|object $class
     * @param string $property
     * @return ReflectionMethod
     */
    public static function factory($class, $method = null)
    {
        if (is_object($class))
            $class = get_class($class);
        $class = (string)$class;
        if (!isset(self::$methods[$class])) {
            $classReflection = new \ReflectionClass($class);
            $methods = $classReflection->getMethods();
            self::$methods[$class] = array();
            foreach ($methods as $methodReflection) {
                if ($methodReflection->class != $class) {
                    self::$methods[$class][$methodReflection->name] = self::factory($methodReflection->class, $methodReflection->name); 
                } else {
                    self::$methods[$class][$methodReflection->name] = new self($class, $methodReflection->name);
                    self::$methods[$class][$methodReflection->name]->setAccessible(true);
                }
            }
        }
        if (isset($method)) {
            if (!isset(self::$methods[$class][$method])) {
                self::$methods[$class][$method] = new self($class, $method);
                self::$methods[$class][$method]->setAccessible(true);
            }
            return self::$methods[$class][$method];
        } else
            return array_values(self::$methods[$class]);
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
