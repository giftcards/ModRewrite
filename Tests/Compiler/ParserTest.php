<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 3:39 PM
 */

namespace Giftcards\ModRewrite\Tests\Compiler;


use Giftcards\ModRewrite\Compiler\Directive;
use Giftcards\ModRewrite\Compiler\Configuration;
use Giftcards\ModRewrite\Compiler\Parser;
use Giftcards\ModRewrite\Compiler\Rule;
use Giftcards\ModRewrite\Tests\TestCase;

class ParserTest extends TestCase
{
    /** @var  Parser */
    protected $parser;

    public function setUp()
    {
        $this->parser = new Parser();
    }

    public function testParse()
    {
        $directives = array(
            new Directive('', Directive::TYPE_ENGINE, '', 'On', array()),
            new Directive('', Directive::TYPE_CONDITION, 'subj', 'pred', array()),
            new Directive('', Directive::TYPE_CONDITION, 'subj2', 'pred2', array()),
            new Directive('', Directive::TYPE_RULE, 'from', 'to', array()),
            new Directive('', Directive::TYPE_CONDITION, 'subj3', 'pred3', array()),
            new Directive('', Directive::TYPE_RULE, 'from2', 'to2', array()),
            new Directive('', Directive::TYPE_MAP, 'name', 'map', array()),
            new Directive('', Directive::TYPE_RULE, 'from3', 'to3', array()),
            new Directive('', Directive::TYPE_OPTIONS, '', 'Inherit', array()),
        );
        
        $engine = new Configuration();
        $engine
            ->setEngineOn(true)
            ->addMap($directives[6])
            ->addOption($directives[8])
            ->addRule(new Rule($directives[3], array($directives[1], $directives[2])))
            ->addRule(new Rule($directives[5], array($directives[4])))
            ->addRule(new Rule($directives[7], array()))
        ;
        $this->assertEquals($engine, $this->parser->parse($directives));
    }
}
