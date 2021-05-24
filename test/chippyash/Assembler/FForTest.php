<?php
/**
 * Assembler-Builder
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2015, UK
 * @license GBSD 3 Clause See LICENSE.md
 */
declare(strict_types=1);
namespace Assembler\Test\Assembler;

use Assembler\FFor;
use PHPUnit\Framework\TestCase;

class FForTest extends TestCase
{
    public function testYouCannotConstructASingletonFfor()
    {
        $this->expectException(\BadMethodCallException::class);
        FFor::get();
    }

    public function testYouCannotMergeAFfor()
    {
        $this->expectException(\BadMethodCallException::class);
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
