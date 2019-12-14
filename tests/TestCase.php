<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function checkMethodExist($object, $method)
    {
        $this->assertTrue(
        method_exists($object, $method),
        get_class($object) . ' does not have method ' . $method
        );
    }

    public function checkFunctionExist($method)
    {
        $this->assertTrue(
        function_exists($method),
        'does not have method '.$method
        );
    }

    /**
    * Call protected/private method of a class.
    *
    * @param object &$object Instantiated object that we will run method on.
    * @param string $methodName Method name to call
    * @param array $parameters Array of parameters to pass into method.
    * @return mixed Method return.
    */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
    * Set the private property of the class
    *
    * @param object $instance Instantiated object that we will run method on.
    * @param string $propertyName Property name to be set
    * @param mixed $propertyVal Value of the property
    */
    public function setPrivateProperty(&$instance, $propertyName, $propertyVal)
    {
        $reflector = new \ReflectionClass(get_class($instance));;
        $reflector_property = $reflector->getProperty($propertyName);
        $reflector_property->setAccessible(true);
        $reflector_property->setValue($instance, $propertyVal);
    }

}
