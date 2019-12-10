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
use Mockery;
use Mockery\MockInterface;
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;

class CompilerTest extends AbstractExtendableTestCase
{
    /** @var  Compiler */
    protected $compiler;
    /** @var  MockInterface */
    protected $lexer;
    /** @var  MockInterface */
    protected $parser;

    public function setUp() :void
    {
        $this->compiler = new Compiler(
            $this->lexer = Mockery::mock('Giftcards\ModRewrite\Compiler\Lexer'),
            $this->parser = Mockery::mock('Giftcards\ModRewrite\Compiler\Parser')
        );
    }

    public function testCompile()
    {
        $content = 'sdfsdfsdf';
        $directives = [
            new Directive('', '', '', '', []),
            new Directive('', '', '', '', []),
        ];
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
