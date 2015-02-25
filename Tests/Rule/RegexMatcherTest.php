<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 7:02 PM
 */

namespace Giftcards\ModRewrite\Tests\Rule;


use Giftcards\ModRewrite\Compiler\Directive;
use Giftcards\ModRewrite\Compiler\Rule;
use Giftcards\ModRewrite\MatchState;
use Giftcards\ModRewrite\Rule\RegexMatcher;
use Giftcards\ModRewrite\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RegexMatcherTest extends TestCase
{
    /** @var  RegexMatcher */
    protected $matcher;

    public function setUp()
    {
        $this->matcher = new RegexMatcher();
    }

    public function testRuleMatchesWhereNotMatch()
    {
        $pathInfo = '/not-it';
        $rule = new Rule(
            new Directive('', Directive::TYPE_RULE, '^/it(.*)', '/other-path', array()),
            array()
        );
        $state = new MatchState($rule, $pathInfo, new Request());
        $this->assertFalse($this->matcher->ruleMatches($pathInfo, $rule, $state));
        $this->assertEmpty($state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_REWRITE));
    }

    public function testRuleMatchesWhereMatch()
    {
        $pathInfo = '/it-oh-yeah';
        $rule = new Rule(
            new Directive('', Directive::TYPE_RULE, '^/it(.*)', '/other-path', array()),
            array()
        );
        $state = new MatchState($rule, $pathInfo, new Request());
        $this->assertTrue($this->matcher->ruleMatches($pathInfo, $rule, $state));
        $this->assertEquals(array(
            '/it-oh-yeah',
            '-oh-yeah'
        ), $state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_REWRITE));
    }
}
