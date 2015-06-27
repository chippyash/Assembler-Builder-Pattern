# chippyash/assembler-builder-pattern

## Quality Assurance

Certified for PHP 5.5+

Badges TBC

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

![Uml diag](https://github.com/chippyash/assembler-builder-pattern/blob/master/docs/uml.png)

Documentation incomplete.

See the tests and [Test Contract](https://github.com/chippyash/assembler-builder-pattern/blob/master/docs/Test-Contract.md)
 for further information at present.

## Running the examples

Although the library itself does not have any other dependencies other than PHP5.4+, 
the examples do.  These are included in the `composer requires-dev` statement so as
long as you have included the dev requirements (default for Composer,) you should be 
good to go.
