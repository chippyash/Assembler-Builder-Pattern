# chippyash/assembler-builder-pattern

## Quality Assurance

Certified for PHP 5.4+

The above badges represent the current development branch.  As a rule, I don't push
 to GitHub unless tests, coverage and usability are acceptable.  This may not be
 true for short periods of time; on holiday, need code for some other downstream
 project etc.  If you need stable code, use a tagged version. Read 'Further Documentation'
 and 'Installation'.
 
## Quality Assurance

Certified for PHP 5.4+

[![Build Status](https://travis-ci.org/chippyash/Monad.svg?branch=master)](https://travis-ci.org/chippyash/Monad)
[![Coverage Status](https://coveralls.io/repos/chippyash/Monad/badge.svg?branch=master)](https://coveralls.io/r/chippyash/Monad?branch=master)
[![Code Climate](https://codeclimate.com/github/chippyash/Monad/badges/gpa.svg)](https://codeclimate.com/github/chippyash/Monad)

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
 but without the associated 
 
I did some searching but couldn't find anything remotely like it, so if you know of anything
please do let me know.  So why is it useful?

In essence it provides a mechanism to collect together `things` and then assemble them
 at some later point. The things in this case are functions.  Since the introduction
 of Callables (or Closures) in PHP, life has changed a bit for the PHP dev. The 
 anonymous function gives a freedom to do anything (PHP has always had that,) in a much
 simpler way.
 
### It is really simple:

- create an `Assembler`
- attach a bunch of functions to it with a key (variable name) for the function
    - functions can access previously stored variables
- assemble everything (call the functions that have been previously attached)
- access one, some or none of the results

The examples/OneManCoffeeShop.php gives a flavour of the Assembler in action in a 
relatively simple scenario.

## Running the examples

Although the library itself does not have any other dependencies other than PHP5.4+, 
the examples do.  These are included in the `composer requires-dev` statement so as
long as you have included the dev requirements (default for Composer,) you should be 
good to go.

Documentation incomplete.

See the tests and [Test Contract](https://github.com/chippyash/assembler-builder-pattern/blob/master/docs/Test-Contract.md)
 for further information at present.
 
Run examples/OneManCoffeeShop.php to see it in action
