<?php
namespace FSi\Component\Reflection;

class ReflectionClass extends \ReflectionClass
{
    protected static $classes = array();

    /**
     * Constructs a new ReflectionClass object.
     * 
     * @param string|object $class
     * @throws ReflectionException
     * @return ReflectionClass
     */
    final public function __construct($class)
    {
        $bt = debug_backtrace();
        if (!isset($bt[1]['class']) || ($bt[1]['class'] !== __CLASS__))
            throw new ReflectionException('ReflectionClass\' constructor cannot be called from outside the class');
        parent::__construct($class);
    }

    /**
     * Constructs a new ReflectionClass object from class name and store it in cache. 
     * If object already exists in cache it will taken from there instead of creating
     * new object
     * 
     * @param string|object $class
     * @return ReflectionClass
     */
    public static function factory($class)
    {
        if (is_object($class))
            $class = get_class($class);
        $class = (string)$class;
        if (!isset(self::$classes[$class]))
            self::$classes[$class] = new self($class);
        return self::$classes[$class];
    }

    /**
     * Get interfaces ReflectionObjects implemented by class 
     * 
     * @return array
     */
    public function getInterfaces()
    {
        $interfaceNames = $this->getInterfaceNames();
        $interfaces = array();
        foreach ($interfaceNames as $interface)
            $interfaces[$interface] = ReflectionClass::factory($interface);
        return $interfaces;
    }

    /**
     * Get parent ReflectionClass object
     * 
     * @return ReflectionClass|false
     */
    public function getParentClass()
    {
        $parentClass = parent::getParentClass();
        if ($parentClass) {
            return ReflectionClass::factory($parentClass->getName());
        }
        return false;
    }

    /**
     * Get class ReflectionMethod object by method name if exists. 
     * 
     * @throws ReflectionException
     * @return ReflectionMethod
     */
    public function getMethod($method)
    {
        return ReflectionMethod::factory($this->name, $method);
    }

    protected function filter($items, $filter)
    {
        $filtered = array();
        foreach ($items as $item)
            if ($item->getModifiers() & $filter)
                $filtered[] = $item;
        return $filtered;
    }

    /**
     * Get class ReflectionMethods objects array list. 
     * 
     * @return array
     */
    public function getMethods($filter = null)
    {
        $args = func_get_args();
        $methods = ReflectionMethod::factory($this->name);

        if (count($args)) {
            return $this->filter($methods, current($args));
        }
        
        return $methods;   
    }

    /**
     * Get class ReflectionProperty object by property name if exists. . 
     * 
     * @throws ReflectionException
     * @return ReflectionProperty
     */
    public function getProperty($property)
    {
        return ReflectionProperty::factory($this->name, $property);
    }
    
    /**
     * Get class ReflectionProperty objects array list.
     * 
     * @return array
     */
    public function getProperties($filter = null)
    {
        $args = func_get_args();
        $properties = ReflectionProperty::factory($this->name);

        if (count($args)) {
            return $this->filter($properties, current($args));
        }
        
        return $properties;
    }

}
