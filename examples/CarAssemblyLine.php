#!/usr/bin/env php
<?php
/**
 * Lightweight assembly builder pattern
 *
 * Car Assembly Line example using the Assembly Comprehension
 * This example illustrates a synchronous execution of the Comprehension
 *
 * Look - no If statements!
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson <ashley@zf4.biz>, 2015, UK
 * @licence GPLV3+ see LICENSE.MD
 */
include __DIR__ . '/../vendor/autoload.php';

use Assembler\Assembler;
use Assembler\FFor;
use Monad\Match;
use Chippyash\Type\String\StringType as Tyre;
use Chippyash\Type\String\StringType as PaintColour;
use Chippyash\Type\String\StringType as Car;

interface Carmaker
{
    /**
     * @param Assembler $assembler
     * @return Assembler
     */
    public function giveMe(Assembler $assembler);
}

class TyreMaker implements Carmaker
{
    /**
     * @param Assembler $assembler
     * @return \Closure
     */
    public function giveMe(Assembler $assembler)
    {
        return $assembler->tyre(
            Match::on(mt_rand(1,2))
            ->test(1, function(){return function(){return new Tyre('radial');};})
            ->test(2, function(){return function(){return new Tyre('cross-ply');};})
            ->value()
        );
    }
}

class Paintshop implements Carmaker
{
    /**
     * @param Assembler $assembler
     * @return \Closure
     */
    public function giveMe(Assembler $assembler)
    {
        return $assembler->colour(
            Match::on(mt_rand(1,2))
            ->test(1, function(){return function(){return new PaintColour('red');};})
            ->test(2, function(){return function(){return new PaintColour('black');};})
            ->value()
        );
    }
}

class AssemblyLine
{
    /**
     * @param Assembler $assembler
     * @return Car
     */
    public function make(Assembler $assembler)
    {
        list($tyre, $colour) = $assembler->assemble()
            ->release('tyre', 'colour');

        return new Car("I made you a $colour car with $tyre tyres");
    }
}

echo FFor::create()
    ->assembler(function(){return Assembler::create();})
    ->tyre(function($assembler){return (new TyreMaker)->giveMe($assembler);})
    ->colour(function($assembler){return (new Paintshop)->giveMe($assembler);})
    ->assemblyLine(function($assembler){return (new AssemblyLine)->make($assembler);})
    ->fyield('assemblyLine');