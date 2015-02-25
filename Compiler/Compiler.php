<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 4:22 PM
 */

namespace Giftcards\ModRewrite\Compiler;


class Compiler
{
    protected $lexer;
    protected $parser;

    public function __construct(Lexer $lexer, Parser $parser)
    {
        $this->lexer = $lexer;
        $this->parser = $parser;
    }

    public function compile($content)
    {
        return $this->parser->parse($this->lexer->lex($content));
    }
}