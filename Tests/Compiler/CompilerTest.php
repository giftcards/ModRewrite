<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 4:24 PM
 */

namespace Giftcards\ModRewrite\Tests\Compiler;


use Giftcards\ModRewrite\Compiler\Compiler;
use Giftcards\ModRewrite\Compiler\Directive;
use Giftcards\ModRewrite\Compiler\Configuration;
use Giftcards\ModRewrite\Tests\TestCase;
use Mockery\MockInterface;

class CompilerTest extends TestCase
{
    /** @var  Compiler */
    protected $compiler;
    /** @var  MockInterface */
    protected $lexer;
    /** @var  MockInterface */
    protected $parser;

    public function setUp()
    {
        $this->compiler = new Compiler(
            $this->lexer = \Mockery::mock('Giftcards\ModRewrite\Compiler\Lexer'),
            $this->parser = \Mockery::mock('Giftcards\ModRewrite\Compiler\Parser')
        );
    }

    public function testCompile()
    {
        $content = 'sdfsdfsdf';
        $directives = array(
            new Directive('', '', '', '', array()),
            new Directive('', '', '', '', array()),
        );
        $engine = new Configuration();
        $this->lexer
            ->shouldReceive('lex')
            ->once()
            ->with($content)
            ->andReturn($directives)
        ;
        $this->parser
            ->shouldReceive('parse')
            ->once()
            ->with($directives)
            ->andReturn($engine)
        ;
        $this->assertEquals($engine, $this->compiler->compile($content));
    }
}
