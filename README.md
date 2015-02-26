Mod Rewrite [![Build Status](https://travis-ci.org/giftcards/ModRewrite.svg?branch=master)](https://travis-ci.org/giftcards/ModRewrite)
===========

Purpose
-------
The mod rewrite library is meant to be a php implementation of mod rewrite. it can read the
same directives given to the apache rewrite engine module and use them to rewrite given urls the
same way as apache would.

Support
-------
RewriteRule, RewriteCond, RewriteEngine are supported with the caveat that certain
server variables are not implemented yet in the replacements system as well as a bunch of the
more advanced rewriting flags for the RewriteRule directive arent implemented. Adding support
for these features as well as the other directives is pretty straightforward through
extension it just hasn't been done yet. At the moment only the server variables QUERY_STRING,
 REQUEST_URI, REQUEST_FILENAME, HTTP_HOST are supported.
Feel free to PR support for more!
  
Usage
-----

### Basic ###

if you just want to use the stock rewriter with a file you just need to use the 2 builders
to build the rewriter and the file compiler

```php
<?php

use Giftcards\ModRewrite\RewriterBuilder;
use Giftcards\ModRewriter\Compiler\CompilerBuilder;
use Symfony\Component\HttpFoundation\Request;

$rewriter = RewriterBuilder::create()->build();
$compiler = CompilerBuilder::create()->build();

$request = Request::createFromGlobals();
$file = __DIR__.'/rewrite_rules';

$result = $rewriter->rewrite(
    $request->getPathInfo(),
    $request,
    $compiler->compile(file_get_contents($file))
);

```

the `$result` is an instance of `Giftcards\ModRewrite\Result` you can get the rewritten
url from `getUrl()` this will be populated whether it was matched and rewritten by a
rule or not. You can get the rule that was matched from `getMatchedRule()`.
the rule contains the rewrite directive as well as the condition directives. 
If no rule was matched `getMatchedRule()` will return `null`.

###Formatters###

Formatters are the clases that do all the replacing of values in the `TestString`
 and `CondPattern` in the `RewriteCond` as well as the `Substitution` in the `RewriteRule`
 in the default rewriter setup. In the `Formatters` dir there are a bunch there
 
- ApacheServerVariableFormatter - this class takes the string and does the replacement for the 
    server variables like HTTP_HOST and REQUEST_URI
- LastPassingConditionFormatter - this class is the one that replaces all the backreferences
    from the regex of the last passing condition
- PathInfoFormatter - if you put a dash as the value in any of the 3 formatted values it will replace
   it with the url that was passed to be rewritten
- RewriteFormatter - this class is the one that replaces all the backreferences form the
   regex from the rule that matched
   
by default all of these are added to a chain formatter and run in the order they were added
you can add as many formatters as you want they have to implement 
`Giftcards\ModRewrite\Formatter\FormaterInterface`

###Condition Predicate Checkers###

the condition predicate checkers are the classes that after a rule matches are used to check
the conditions attached to the rule.
in the Condition there are a bunch already defined

- ExistencePredicateChecker - this is the one that does checks for the -f and -d flags to
    see if the file or dir mentioned exists
- FormattingPredicateChecker - this checker gets passed another checker to actually
    do the checking with as well as a formatter which it uses to format the test string
    as well as the condition pattern before passing it to the real checker. this class allows
    for condition backreferences rewrite backreferences etc to be used in these values
- NotPredicateChecker - this class allows for the ! char to be used in a condition
   if a ! is there it strips it then passes it to the real checker to do its thing
   then returns the opposite of the result
- RegexPredicateChecker - this treats the condition pattern as a regex and does the check
    on the test stirng that way
    
all predicate checkers have a supports method. this allows them to be added to a chain checker
which will check to see if any checkers in the chain support the condition pattern (predicate)
the chain will throw an exception if no supporting checker is found. the chain allows you
to set a default checker to run instead if you dont want an exception thrown. in the default setup
the existence checker is added to the chain, the regex checker is set as the default. the chain
then passed into the not checker which is passed into the formatting checker.