<?php
/**
 * Lightweight assembly builder pattern
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson <ashley@zf4.biz>, 2016,2021 UK
 * @licence BSD 3 Clause see LICENSE.MD
 */
declare(strict_types=1);
namespace Assembler\Traits;

/**
 * Trait that can grab parameter values from a method or function being called
 * Useful as a helper when creating an Assembler or FFor
 *
 * usage:
 *
 * use Assembler\Traits\ParameterGrabable;
 * use Assembler\Assembler;
 *
 * class myClass {
 *      use ParameterGrabable;
 *
 *      static function foo($param1, $param2 = null) {
 *          $a = Assembler::create(self::grabFunctionParameters(__CLASS__, __FUNCTION__, func_get_args());
 *      }
 *
 *      function bar($param1, $param2 = null) {
 *          $a = Assembler::create($this->grabMethodParameters(__CLASS__, __METHOD__, func_get_args());
 *      }
 * }
 */
trait ParameterGrabable
{

    /**
     * Return array keyed by parameter name of values passed into a class static function
     *
     * @param string $class Name of class that has the method usually via __CLASS__
     * @param string $function Name of function to inspect usually via __FUNCTION__
     * @param array $paramValues array of actual values usually via func_get_args
     *
     * @return array
     */
    static function grabFunctionParameters($class, $function, array $paramValues): array
    {
        $declaredParams = (new \ReflectionMethod($class, $function))->getParameters();
        $paramNames = array_map(function(\ReflectionParameter $v) {
            return $v->getName();
        },
            $declaredParams
        );
        $paramDefaults = array_map(function(\ReflectionParameter $v) {
            return $v->isOptional() ? $v->getDefaultValue() : null;
        },
            $declaredParams
        );

        return array_combine($paramNames, array_replace($paramDefaults, $paramValues));
    }

    /**
     * Return array keyed by parameter name of values passed into an object method
     *
     * @param string $class Name of class that has the method usually via __CLASS__
     * @param string $method Name of method to inspect usually via __METHOD__
     * @param array $paramValues array of actual values usually via func_get_args
     *
     * @return array
     */
    function grabMethodParameters($class, $method, array $paramValues): array
    {
        $declaredParams = (new \ReflectionMethod($class, $method))->getParameters();
        $paramNames = array_map(function(\ReflectionParameter $v) {
            return $v->getName();
        },
            $declaredParams
        );
        $paramDefaults = array_map(function(\ReflectionParameter $v) {
            return $v->isOptional() ? $v->getDefaultValue() : null;
        },
            $declaredParams
        );

        return array_combine($paramNames, array_replace($paramDefaults, $paramValues));
    }
}