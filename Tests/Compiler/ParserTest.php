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

use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;

class ParserTest extends AbstractExtendableTestCase
{
    /** @var  Parser */
    protected $parser;

    public function setUp() :void
    {
        $this->parser = new Parser();
    }

    public function testParse()
    {
        $directives = [
            new Directive('', Directive::TYPE_ENGINE, '', 'On', []),
            new Directive('', Directive::TYPE_CONDITION, 'subj', 'pred', []),
            new Directive('', Directive::TYPE_CONDITION, 'subj2', 'pred2', []),
            new Directive('', Directive::TYPE_RULE, 'from', 'to', []),
            new Directive('', Directive::TYPE_CONDITION, 'subj3', 'pred3', []),
            new Directive('', Directive::TYPE_RULE, 'from2', 'to2', []),
            new Directive('', Directive::TYPE_MAP, 'name', 'map', []),
            new Directive('', Directive::TYPE_RULE, 'from3', 'to3', []),
            new Directive('', Directive::TYPE_OPTIONS, '', 'Inherit', []),
        ];
        
        $engine = new Configuration();
        $engine
            ->setEngineOn(true)
            ->addMap($directives[6])
            ->addOption($directives[8])
            ->addRule(new Rule($directives[3], [$directives[1], $directives[2]]))
            ->addRule(new Rule($directives[5], [$directives[4]]))
            ->addRule(new Rule($directives[7], []))
        ;
        $this->assertEquals($engine, $this->parser->parse($directives));
    }
}
