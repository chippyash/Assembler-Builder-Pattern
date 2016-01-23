<?php
/**
 * Assembler-Builder
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Assembler\Test\Traits;

use chippyash\Type\String\StringType;

class ParameterGrabableTest extends \PHPUnit_Framework_TestCase
{
    public function testYouCanGrabAnObjectMethodParameters()
    {
        $sut = $this->getObjectForTrait('Assembler\Traits\ParameterGrabable');
        $params = $sut->grabMethodParameters(__CLASS__, 'stubClassMethod', [new StringType('foo'), 100]);
        $this->assertEquals(
            ['param1' => new StringType('foo'), 'param2' => 100, 'param3' => null],
            $params
        );
    }

    public function testYouCanGrabAStaticClassFunctionParameters()
    {
        $this->getObjectForTrait('Assembler\Traits\ParameterGrabable', [], 'MockTrait');
        $params = \MockTrait::grabFunctionParameters(__CLASS__, 'stubClassFunction', [new StringType('foo'), 100]);
        $this->assertEquals(
            ['param1' => new StringType('foo'), 'param2' => 100, 'param3' => null],
            $params
        );
    }

    protected function stubClassMethod(StringType $param1, $param2, $param3 = null)
    {
    }

    static protected function stubClassFunction(StringType $param1, $param2, $param3 = null)
    {
    }
}
