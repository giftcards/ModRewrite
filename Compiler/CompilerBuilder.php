<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/25/15
 * Time: 3:12 PM
 */

namespace Giftcards\ModRewrite\Compiler;


use Giftcards\ModRewrite\Compiler\Compiler;
use Giftcards\ModRewrite\Compiler\Lexer;
use Giftcards\ModRewrite\Compiler\Parser;

class CompilerBuilder
{
    protected $lexer;
    protected $parser;

    public static function create()
    {
        return new static();
    }

    public function build()
    {
        return new Compiler(
            $this->getLexer(),
            $this->getParser()
        );
    }

    /**
     * @return mixed
     */
    public function getLexer()
    {
        if (!$this->lexer) {
            $this->lexer = $this->getDefaultLexer();
        }
        
        return $this->lexer;
    }

    /**
     * @param mixed $lexer
     * @return $this
     */
    public function setLexer(Lexer $lexer)
    {
        $this->lexer = $lexer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParser()
    {
        if (!$this->parser) {
            $this->parser = $this->getDefaultParser();
        }
        
        return $this->parser;
    }

    /**
     * @param mixed $parser
     * @return $this
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;
        return $this;
    }

    protected function getDefaultLexer()
    {
        return new Lexer();
    }

    protected function getDefaultParser()
    {
        return new Parser();
    }
}