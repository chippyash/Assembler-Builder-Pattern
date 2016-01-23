<?php
/**
 * Lightweight assembly builder pattern
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson <ashley@zf4.biz>, 2015, UK
 * @licence GPLV3+ see LICENSE.MD
 */

namespace Assembler;

/**
 * A Class that assembles other things to give a result
 *
 */
class Assembler
{
    /**
     * Names of introduced variables
     *
     * @var array
     */
    protected $placeHolders = [];

    /**
     * Computed values
     *
     * @var array
     */
    protected $values = [];

    /**
     * Singleton instance of an Assembler
     * @see get()
     *
     * @var Assembler
     */
    private static $singleton;

    /**
     * Static Assembler constructor
     * Returns a new Assembler
     *
     * @param array $params Immutable parameters to send into the assembler
     *
     * @return static
     */
    public static function create(array $params = [])
    {
        $assembler = new static();
        if (!empty($params)) {
            array_walk($params, function($v, $k) use ($assembler) {
                $assembler->$k(function() use($v) {return $v;});
            });
            $assembler->assemble();
        }

        return $assembler;
    }

    /**
     * Return Singleton instance of Assembler
     *
     * @param array $params Immutable parameters to send into the assembler
     *
     * @return Assembler
     */
    public static function get(array $params = [])
    {
        if (empty(self::$singleton)) {
            self::$singleton = static::create($params);
        }

        return self::$singleton;
    }

    /**
     * Proxies variable names to functions
     *
     * You can overwrite an existing definition
     *
     * @param $method
     * @param array $args
     * @return $this
     */
    public function __call($method, array $args)
    {
        if (count($args) == 0 || !$args[0] instanceof \Closure) {
            throw new \RuntimeException('We expect to build the variable with a function');
        }
        $this->placeHolders[$method] = $args[0];

        return $this;
    }

    /**
     * Return an array of variables created else just a single value
     * Usage:
     * ->release('var1','varN')
     * ->release('var1')
     *
     * Don't forget to call assemble() first if needed
     *
     * @param string $var1 Name of variable to return
     * @param string $_ Next name of variable to retrieve - repeater
     *
     * @return mixed
     */
    public function release($var1)
    {
        $flip = array_flip(func_get_args());
        $intersect = array_intersect_key($this->values, $flip);
        if (count(func_get_args()) > 1) {
            array_walk($flip, function(&$v, $k) use ($intersect) {
                 $v = $intersect[$k];
            });
            return array_values($flip);
        } else {
            return array_pop($intersect);
        }
    }

    /**
     * Run the Assembly not returning any value.
     * Assembly will not overwrite anything already assembled
     *
     * @return $this
     */
    public function assemble()
    {
        foreach ($this->placeHolders as $name => $placeholder) {
            if (!isset($this->values[$name])) {
                $this->values[$name] = $this->addVars($placeholder);
            }
        }

        return $this;
    }

    /**
     * Merge this assembly with another.
     * Obeys the rules of array_merge
     *
     * @param Assembler $other
     *
     * @return $this
     */
    public function merge(Assembler $other)
    {
        //N.B. reflection used so as to not expose values
        //via some public method
        $refl = new \ReflectionObject($other);
        $reflValues = $refl->getProperty('values');
        $reflValues->setAccessible(true);
        $oValues = $reflValues->getValue($other);
        if (count($oValues) == 0) {
            return $this;
        }

        $this->values = array_merge($this->values, $oValues);

        return $this;
    }

    /**
     * Execute the function assigned to the value, binding in values created earlier
     * in the assembly
     *
     * @param \Closure $func
     *
     * @return mixed
     */
    protected function addVars(\Closure $func)
    {
        $args = (new \ReflectionFunction($func))->getParameters();
        if (count($args) === 0) {
            return $func();
        }

        $fArgs = array_map(function($key) {
            return $this->values[$key];
        },
            array_map(function($arg) {
                return $arg->getName();
            },
                $args
            )
        );

        return call_user_func_array($func, $fArgs);
    }

    /**
     * Don't allow direct construction
     * @see create()
     */
    protected function __construct(){}
}