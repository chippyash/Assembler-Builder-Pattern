#!/bin/bash
cd ~/Projects/chippyash/source/Assembler-Builder
vendor/phpunit/phpunit/phpunit -c test/phpunit.xml --testdox-html contract.html test/
tdconv -t "Chippyash Assembler Builder Pattern" contract.html docs/Test-Contract.md
rm contract.html

