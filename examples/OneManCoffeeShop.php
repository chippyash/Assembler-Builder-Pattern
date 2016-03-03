#!/usr/bin/env php
<?php
/**
 * Lightweight assembly builder pattern
 *
 * The One Man Coffee Shop example using the Assembly Comprehension
 * This example illustrates a synchronous execution of the Comprehension
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson <ashley@zf4.biz>, 2015, UK
 * @licence GPLV3+ see LICENSE.MD
 */
include __DIR__ . '/../vendor/autoload.php';

use Chippyash\Type\Number\IntType as Water;
use Chippyash\Type\String\StringType as CoffeeBeans;
use Chippyash\Type\String\StringType as GroundCoffee;
use Chippyash\Type\String\StringType as Milk;
use Chippyash\Type\String\StringType as FrothedMilk;
use Chippyash\Type\String\StringType as Espresso;
use Chippyash\Type\String\StringType as Cappuccino;
use Assembler\Assembler;

class CoffeeShop
{
    public function grind(CoffeeBeans $beans)
    {
        $this->println("started grinding");
        usleep(mt_rand(500000,1000000));
        $this->println("finished grinding");

        return new GroundCoffee("ground coffee of {$beans} beans");
    }

    public function heatWater(Water $water)
    {
        $this->println("heating water");
        usleep(mt_rand(500000,1000000));
        $this->println("water heated");

        return new Water(85);
    }


    public function frothMilk(Milk $milk)
    {
        $this->println("frothing milk");
        usleep(mt_rand(500000,1000000));
        $this->println("milk frothed");

        return new FrothedMilk("frothed {$milk} milk");
    }

    public function brew(GroundCoffee $coffee, Water $heatedWater)
    {
        $this->println("brewing coffee");
        usleep(mt_rand(500000,1000000));
        $this->println("coffee brewed");

        return new Espresso("espresso");
    }

    public function combine(Espresso $espresso, FrothedMilk $frothedMilk)
    {
        $this->println("combining");
        usleep(mt_rand(500000,1000000));
        $this->println("combined");

        return new Cappuccino('cappuccino');
    }

    protected function println($str) {
        print "{$str}\n";
        @ob_flush();
    }

    public function makeCappuccino()
    {
        return Assembler::create()
            ->ground(function(){return $this->grind(new CoffeeBeans("Arabica"));})
            ->water(function(){return $this->heatWater(new Water(25));})
            ->espresso(function($ground, $water){return $this->brew($ground, $water);})
            ->foam(function(){return $this->frothMilk(new Milk("skinny"));})
            ->combine(function($espresso, $foam){return $this->combine($espresso, $foam);})
            ->assemble()
            ->release('combine', 'ground', 'water', 'foam');
    }
}

print "\nOne moment please, making a cappuccino ...\n";

list($drink, $grind, $water, $milk) = (new CoffeeShop)->makeCappuccino();

print "\nHere is your $drink using $grind with water at a temperature of {$water}C and $milk\n";