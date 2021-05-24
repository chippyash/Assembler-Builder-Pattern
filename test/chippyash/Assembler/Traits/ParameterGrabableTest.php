<?php
/**
 * Assembler-Builder
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016,2021 UK
 * @license BSD 3 Clause See LICENSE.md
 */

namespace Assembler\Test\Traits;

use Assembler\Traits\ParameterGrabable;
use PHPUnit\Framework\TestCase;

class ParameterGrabableTest extends TestCase
{
    public function testYouCanGrabAnObjectMethodParameters()
    {
        $sut = $this->getObjectForTrait(ParameterGrabable::class);
        $params = $sut->grabMethodParameters(__CLASS__, 'stubClassMethod', ['foo', 100]);
        $this->assertEquals(
            ['param1' => 'foo', 'param2' => 100, 'param3' => null],
            $params
        );
    }

    public function testYouCanGrabAStaticClassFunctionParameters()
    {
        $this->getObjectForTrait(ParameterGrabable::class, [], 'MockTrait');
        $params = \MockTrait::grabFunctionParameters(__CLASS__, 'stubClassFunction', ['foo', 100]);
        $this->assertEquals(
            ['param1' => 'foo', 'param2' => 100, 'param3' => null],
            $params
        );
    }

    protected function stubClassMethod(string $param1, $param2, $param3 = null)
    {
    }

    static protected function stubClassFunction(string $param1, $param2, $param3 = null)
    {
    }
}
