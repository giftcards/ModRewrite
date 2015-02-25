<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/12/15
 * Time: 10:18 PM
 */

namespace Giftcards\ModRewrite\Compiler;


class Lexer
{
    public function lex($contents)
    {
        preg_match_all(
            '/^(Rewrite(?:Engine|Cond|Rule|Options|Base|Map|Log|LogLevel))(?:\s+([^\s]+))?\s+([^\s]+)(?:\s+\[([^\s]+)\])?\s*(?:\n|$)/m', 
            $contents,
            $matches,
            PREG_SET_ORDER
        );
       
        /** @var Directive[] $directives */
        return array_map(function(array $matches)
        {
            return Directive::createFromMatches($matches);
        }, $matches);
    }
}