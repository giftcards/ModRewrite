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
server variables are not implemented yet in the replacements system. adding support
for more variable as well as the other directives is pretty straightforward through
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
use Giftcards\ModRewriter\CompilerBuilder;
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

the `$result` is an instance of `Giftcards\ModRewrite\Result` you can get the rewriten
url from `getUrl()` this will be populated wether it was matched and rewritten by a
rule or not. you can get if there should be a redirect and the status code to use for
it from `getRedirect()` and you can get the rule that was matched from `getMatchedRule()`.
the rule contains the rewrite directive as well as the condition directives. 
If no rule was matched `getMatchedRule()` will return `null`.