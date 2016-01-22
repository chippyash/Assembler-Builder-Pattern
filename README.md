# chippyash/Assembly-Builder

## Quality Assurance

Certified for PHP 5.4+

[![Build Status](https://travis-ci.org/chippyash/Assembly-Builder.svg?branch=master)](https://travis-ci.org/chippyash/Assembly-Builder)
[![Code Climate](https://codeclimate.com/github/chippyash/Assembly-Builder/badges/gpa.svg)](https://codeclimate.com/github/chippyash/Assembly-Builder)
[![Test Coverage](https://codeclimate.com/github/chippyash/Assembly-Builder/badges/coverage.svg)](https://codeclimate.com/github/chippyash/Assembly-Builder/coverage)

The above badges represent the current development branch.  As a rule, I don't push
 to GitHub unless tests, coverage and usability are acceptable.  This may not be
 true for short periods of time; on holiday, need code for some other downstream
 project etc.  If you need stable code, use a tagged version. Read 'Further Documentation'
 and 'Installation'.
 
## What?

Provides an Assembler, a lightweight variant of the Builder Pattern.  Also provides a
Scala like For Comprehension, (a simple descendant of Assembler.)

## Why?

In my research of the Scala language, I came across the [For Comprehension](http://the-matrix.github.io/php/a-functional-for-comprehension/).
 It turns out that at its core, it is really a variant of a classic [Builder Pattern](https://github.com/chippyash/Builder-Pattern),
 but without the associated requirements for a Director.
 
I did some searching but couldn't find anything remotely like it, so if you know of anything
please do let me know.  

So why is it useful?

In essence it provides a mechanism to collect together `things` and then assemble them
 at some later point. The things in this case are functions.  Since the introduction
 of Callables (or Closures) in PHP, life has changed a bit for the PHP dev. The 
 anonymous function gives a freedom to do anything (PHP has always had that,) in a much
 simpler way.
 
### It is really simple:

- create an `Assembler`
- attach a bunch of functions to it with a key (variable name) for the function
    - functions can access previously defined functions (or stored variables)
- assemble everything (call the functions that have been previously attached)
- access one, some or none of the results

The examples/OneManCoffeeShop.php script gives a flavour of the Assembler in action in a 
relatively simple scenario.

examples/CarAssemblyLine.php is a bit more complex, but shows how you can pass around
an Assembler to various processes a bit like a shopping trolley, leaving it until some
point in the future where it is all assembled into some final product.  It also demonstrates
a simple derivation of the Assembler, the Scala like `For Comprehension` which because
`For` is a reserved word in PHP, is called `FFor`.

In a large system, you might want to use an Assembler as a collection point for stuff
going on in the application.  For this purpose you can get a Singleton instance via
`Assembler::get()`. Of course, you can only use this once (as in subsequent calls to
Assembler::get() will return the same instance,) so use with care. 

## How?

### Assembler

Create an Assembler

<pre>
use Assembler\Assembler;

$myAssembler = Assembler::create();
//or to create the singleton instance
$myAssembler = Assembler::get();
</pre>

Add functions to an Assembler. This may seems strange at first. The pattern for adding
functions is:

<pre>
Assembler->nameOfVar(function(){ return ...;});
//e.g.
$myAssembler->foo(function(){return 'foo';});
</pre>

Or to chain a number of assembly items together:

<pre>
$myAssembler->foo(function(){return 'foo';})
    ->bar(function(){return 'bar';});
</pre>

You can reference predefined entries by passing in their name as a parameter to 
subsequent entries:

<pre>
$myAssembler->foo(function(){return 'foo';})
    ->bar(function($foo){return "$foo bar";});
</pre>

At this point, the Assembler has not executed your functions, so you can redefined them:

<pre>
$myAssembler->foo(function(){return 'foo';})
    ->bar(function($foo){return "$foo bar";})
    ->foo(function(){return 'foo foo';});
</pre>

To execute the functions, call the `assemble()` method:

<pre>
$myAssembler->foo(function(){return 'foo})
    ->bar(function($foo){return "$foo bar";})
    ->foo(function(){return 'foo foo';})
    ->assemble();
</pre>

At this point, the entries become immutable and cannot be overwritten.  You can
continue to add additional entries, perhaps referencing earlier ones and then call
->assemble() again to fix the entries.

To retrieve one of more values from the Assembler you use the `release()` method.
release() takes one or more strings, the names of the items that you want to release.
To release a single item:

<pre>
$myFoo = Assembler::create()
    ->foo(function(){return 'foo})
    ->bar(function($foo){return "$foo bar";})
    ->foo(function(){return 'foo foo';})
    ->assemble()
    ->release('foo');
</pre>

Releasing multiple items will return an array of values, so perhaps the easiest way
to access them is to use the venerable PHP `list()` method, e.g.

<pre>
list($myFoo, $myBar) = Assembler::create()
    ->foo(function(){return 'foo})
    ->bar(function($foo){return "$foo bar";})
    ->foo(function(){return 'foo foo';})
    ->assemble()
    ->release('foo', 'bar');
</pre>

You can merge one Assembler into another using the `merge()` method:

<pre>
$worker1 = Assembler::create()
    ->foo(function(){return 'foo});
    
$worker2 = Assembler::create()
    ->bar(function($foo){return "$foo bar";})
    
$myFoo = $worker1->merge($worker2->assemble())
    ->assemble()
    ->release('foo');
</pre>

You can send in parameters during the creation (create() or get()) of an Assembler.
This is most useful to prevent you having to use the `use clause` during function
definition.  Parameters sent in during the create process are immutable, i.e. you cannot
override them with a later declaration.

<pre>
$a = 'foo';
$b = 'bar'

$value = Assembler::create(['a'=>$a, 'b'=>$b])
    ->foo(function($a, $b) { return "$a$b";})
    ->assemble()
    ->release('foo');
// $value == 'foobar'

//This will have no effect on 'a'
$value = Assembler::create(['a'=>$a, 'b'=>$b])
    ->a(function() {return 1;})
    ->foo(function($a, $b) { return "$a$b";})
    ->assemble()
    ->release('foo');

//without parameter injection
$value = Assembler::create()
    ->foo(function() use ($a, $b) { return "$a$b";})
    ->assemble()
    ->release('foo');

</pre>

### FFor

The FFor class is a simple extension of Assembler, but with restrictions:

- you cannot create a singleton FFor via get(). Use create().  FFor is intended as a language
construct
- you cannot merge() a FFor.
- there is an additional method; fyield(). fyield() is a pseudonym for ->assemble()->release()
and takes the same parameters as release()

See the examples/CarAssemblyLine.php script for a usage example.

## Further documentation

Please note that what you are seeing of this documentation displayed on Github is
always the latest dev-master. The features it describes may not be in a released version
 yet. Please check the documentation of the version you Compose in, or download.

![Uml diag](https://github.com/chippyash/Assembly-Builder/blob/master/docs/uml.png)

See the tests and [Test Contract](https://github.com/chippyash/Assembly-Builder/blob/master/docs/Test-Contract.md)
 for further information.

## Running the examples

Although the library itself does not have any other dependencies other than PHP5.4+, 
the examples do.  These are included in the `composer requires-dev` statement so as
long as you have included the dev requirements (default for Composer,) you should be 
good to go.

## Changing the library

1.  fork it
2.  write the test
3.  amend it
4.  do a pull request

Found a bug you can't figure out?

1.  fork it
2.  write the test
3.  do a pull request

NB. Make sure you rebase to HEAD before your pull request

Or - raise an issue ticket.

## Where?

The library is hosted at [Github](https://github.com/chippyash/Assembly-Builder). It is
available at [Packagist.org](https://packagist.org/packages/chippyash/assembly-builder)

### Installation

Install [Composer](https://getcomposer.org/)

#### For production

<pre>
    "chippyash/assembly-builder": "~1.1.0"
</pre>

Or to use the latest, possibly unstable version:

<pre>
    "chippyash/assembly-builder": "dev-master"
</pre>


#### For development

Clone this repo, and then run Composer in local repo root to pull in dependencies

<pre>
    git clone git@github.com:chippyash/Assembly-Builder.git Assembler
    cd Monad
    composer install
</pre>

To run the tests:

<pre>
    cd Assembler
    vendor/bin/phpunit -c test/phpunit.xml test/
</pre>

## License

This software library is released under the [GNU GPL V3 or later license](http://www.gnu.org/copyleft/gpl.html)

This software library is Copyright (c) 2015, Ashley Kitson, UK

A commercial license is available for this software library, please contact the author. 
It is normally free to deserving causes, but gets you around the limitation of the GPL
license, which does not allow unrestricted inclusion of this code in commercial works.

## History

V1.0.0 Initial Release

V1.1.0 Add ability to send in parameters on Assembler and Ffor creation
 