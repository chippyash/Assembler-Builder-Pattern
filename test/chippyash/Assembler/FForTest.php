<?php
/**
 * Assembler-Builder
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace chippyash\Test\Assembler;

use Assembler\FFor;

class FForTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException BadMethodCallException
     */
    public function testYouCannotConstructASingletonFfor()
    {
        FFor::get();
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testYouCannotMergeAFfor()
    {
        FFor::create()->merge(FFor::create());
    }

    public function testYouCanFyieldAFforInsteadOfAssembleThenReturn()
    {
        $test = FFor::create()
            ->a(function(){return 1;})
            ->fyield('a');
        $this->assertEquals(1, $test);
    }
}
