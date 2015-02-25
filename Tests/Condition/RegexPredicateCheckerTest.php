<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 7:02 PM
 */

namespace Giftcards\ModRewrite\Tests\Condition;


use Giftcards\ModRewrite\Condition\RegexPredicateChecker;
use Giftcards\ModRewrite\MatchState;
use Giftcards\ModRewrite\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RegexPredicateCheckerTest extends TestCase
{
    /** @var  RegexPredicateChecker */
    protected $checker;

    public function setUp()
    {
        $this->checker = new RegexPredicateChecker();
    }

    public function testCheckPredicateWhereNotMatch()
    {
        $predicate = '^/it(.*)';
        $subject = '/not-it';
        $flags = array();
        $pathInfo = $this->getFaker()->word;
        $state = new MatchState(
            \Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'),
            $pathInfo,
            new Request()
        );

        $this->assertFalse($this->checker->checkPredicate($predicate, $subject, $flags, $state));
        $this->assertEmpty($state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_CONDITION));
        $subject = '/IT-oh-yeah';
        $this->assertFalse($this->checker->checkPredicate($predicate, $subject, $flags, $state));
        $this->assertEmpty($state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_CONDITION));
    }

    public function testSupports()
    {
        $this->assertTrue($this->checker->supports(''));
    }

    public function testRuleMatchesWhereMatch()
    {
        $predicate = '^/it(.*)';
        $subject = '/it-oh-yeah';
        $flags = array();
        $pathInfo = $this->getFaker()->word;
        $state = new MatchState(
            \Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'),
            $pathInfo,
            new Request()
        );
        $this->assertTrue($this->checker->checkPredicate($predicate, $subject, $flags, $state));
        $this->assertEquals(array(
            '/it-oh-yeah',
            '-oh-yeah'
        ), $state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_CONDITION));
    }

    public function testRuleMatchesWhereCaseInsensitiveMatch()
    {
        $predicate = '^/it(.*)';
        $subject = '/IT-oh-yeah';
        $flags = array(
            'NC' => true
        );
        $pathInfo = $this->getFaker()->word;
        $state = new MatchState(
            \Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'),
            $pathInfo,
            new Request()
        );
        $this->assertTrue($this->checker->checkPredicate($predicate, $subject, $flags, $state));
        $this->assertEquals(array(
            '/IT-oh-yeah',
            '-oh-yeah'
        ), $state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_CONDITION));
    }
}
