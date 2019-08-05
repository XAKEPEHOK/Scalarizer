<?php
/**
 * Datetime: 05.08.2019 10:24
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace XAKEPEHOK\Scalarizer;


use JsonSerializable;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class Scalarizer
{

    /**
     * @var bool
     */
    private $useReflection;
    /**
     * @var array
     */
    private $rules;

    /**
     * Scalarizer constructor.
     * @param array $rules - associative array, where every key is a classname, and value is a callable, that can convert
     * class to scalar value
     * @param bool $useReflection - check class by reflection and try to return scalar value, if class has only one property.
     * Reflection has a lowest priority
     */
    public function __construct(
        array $rules = [],
        bool $useReflection = false
    )
    {
        $this->rules = $rules;
        $this->useReflection = $useReflection;
    }

    /**
     * @param mixed $value
     * @return bool|float|int|mixed|string|null
     * @throws ScalarizerException
     * @throws ReflectionException
     */
    public function scalarize($value)
    {
        if ($this->isScalar($value)) {
            return $value;
        }

        if ($this->hasRule($value)) {
            return $this->extractByRules($value);
        }

        if (is_object($value)) {

            if ($value instanceof JsonSerializable) {
                $serialized = $value->jsonSerialize();
                if ($this->isScalar($serialized)) {
                    return $serialized;
                }
            }

            if (method_exists($value, '__toString')) {
                return (string) $value;
            }

            if ($this->useReflection) {
                $extracted = $this->extractByReflection($value);
                if ($this->isScalar($extracted)) {
                    return $extracted;
                }
            }
        }

        $type = gettype($value);
        throw new ScalarizerException("Value with type '{$type}' can not be scalarized", 1);
    }

    private function isScalar($value): bool
    {
        return is_scalar($value) || is_null($value);
    }

    /**
     * @param $object
     * @return bool
     */
    private function hasRule($object): bool
    {
        foreach ($this->rules as $class => $callable) {
            if (is_a($object, $class, true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $object
     * @return mixed
     * @throws ScalarizerException
     */
    private function extractByRules($object)
    {
        foreach ($this->rules as $class => $callable) {
            if (is_a($object, $class, true)) {
                return $callable($object);
            }
        }

        throw new ScalarizerException('No rule for scalarize class ' . get_class($object), 2);
    }

    /**
     * @param $object
     * @return mixed
     * @throws ScalarizerException
     * @throws ReflectionException
     */
    private function extractByReflection($object)
    {
        $reflector = new ReflectionClass($object);
        $properties = array_filter($reflector->getProperties(), function (ReflectionProperty $property) {
            return !$property->isStatic();
        });

        if (count($properties) !== 1) {
            throw new ScalarizerException('Impossible to get single value from ' . get_class($object) . 'via reflection', 3);
        }

        /** @var ReflectionProperty $property */
        $property = current($properties);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

}