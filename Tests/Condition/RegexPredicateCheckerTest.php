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
use Mockery;
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;
use Symfony\Component\HttpFoundation\Request;

class RegexPredicateCheckerTest extends AbstractExtendableTestCase
{
    /** @var  RegexPredicateChecker */
    protected $checker;

    public function setUp() :void
    {
        $this->checker = new RegexPredicateChecker();
    }

    public function testCheckPredicateWhereNotMatch()
    {
        $predicate = '^/it(.*)';
        $subject = '/not-it';
        $flags = [];
        $pathInfo = $this->getFaker()->word;
        $state = new MatchState(
            Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'),
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
        $flags = [];
        $pathInfo = $this->getFaker()->word;
        $state = new MatchState(
            Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'),
            $pathInfo,
            new Request()
        );
        $this->assertTrue($this->checker->checkPredicate($predicate, $subject, $flags, $state));
        $this->assertEquals([
            '/it-oh-yeah',
            '-oh-yeah'
        ], $state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_CONDITION));
    }

    public function testRuleMatchesWhereCaseInsensitiveMatch()
    {
        $predicate = '^/it(.*)';
        $subject = '/IT-oh-yeah';
        $flags = [
            'NC' => true
        ];
        $pathInfo = $this->getFaker()->word;
        $state = new MatchState(
            Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'),
            $pathInfo,
            new Request()
        );
        $this->assertTrue($this->checker->checkPredicate($predicate, $subject, $flags, $state));
        $this->assertEquals([
            '/IT-oh-yeah',
            '-oh-yeah'
        ], $state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_CONDITION));
    }
}
