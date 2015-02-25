<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 5:38 PM
 */

namespace Giftcards\ModRewrite\Tests;


use Giftcards\ModRewrite\Compiler\Directive;
use Giftcards\ModRewrite\Compiler\Rule;
use Giftcards\ModRewrite\MatchState;
use Giftcards\ModRewrite\Result;
use Giftcards\ModRewrite\Rewriter;
use Giftcards\ModRewrite\Compiler\Configuration;
use Giftcards\ModRewrite\Tests\Mock\Mockery\Matcher\EqualsMatcher;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Request;

class RewriterTest extends TestCase
{
    /** @var  Rewriter */
    protected $rewriter;
    /** @var  MockInterface */
    protected $formatter;
    /** @var  MockInterface */
    protected $matcher;
    /** @var  MockInterface */
    protected $checker;

    public function setUp()
    {
        $this->rewriter = new Rewriter(
            $this->formatter = \Mockery::mock('Giftcards\ModRewrite\Formatter\FormatterInterface'),
            $this->matcher = \Mockery::mock('Giftcards\ModRewrite\Rule\MatcherInterface'),
            $this->checker = \Mockery::mock('Giftcards\ModRewrite\Condition\PredicateCheckerInterface')
        );
    }

    public function testRewriteWhereConfigurationHasNoRules()
    {
        $configuration = new Configuration();
        $pathinfo = $this->getFaker()->word;
        $this->assertEquals(new Result($pathinfo), $this->rewriter->rewrite(
            $pathinfo,
            new Request(),
            $configuration
        ));
    }
    
    public function testRewriteWhereNoRuleMatches()
    {
        $pathInfo = 'url';
        $configuration = new Configuration();
        $configuration
            ->addRule(new Rule(
                new Directive('', Directive::TYPE_RULE, 'rtyrtrty', 'bvcbvbcv', array()),
                array()
            ))
            ->addRule(new Rule(
                new Directive('', Directive::TYPE_RULE, 'url', 'adsdas', array()),
                array(
                    new Directive('', Directive::TYPE_CONDITION, 'xxvxc', 'nvnvvb', array()),
                    new Directive('', Directive::TYPE_CONDITION, 'dsadas', 'jgjhghj', array()),
                )
            ))
            ->addRule(new Rule(
                new Directive('', Directive::TYPE_RULE, 'url', 'wwrewre', array()),
                array(
                    new Directive('', Directive::TYPE_CONDITION, 'cbcvcvb', 'xxzcxz', array('OR' => true)),
                    new Directive('', Directive::TYPE_CONDITION, 'ghghjghj', 'bbcvbv', array()),
                )
            ))
        ;
        $rules = $configuration->getRules();
        $conditions2 = $rules[1]->getConditions();
        $conditions3 = $rules[2]->getConditions();
        $request = new Request(array('key' => 'value'));
        $state1 = new MatchState($rules[0], $pathInfo, $request);
        $state2 = new MatchState($rules[1], $pathInfo, $request);
        $state3 = new MatchState($rules[2], $pathInfo, $request);
        $this->matcher
            ->shouldReceive('ruleMatches')
            ->once()
            ->with($pathInfo, $rules[0], new EqualsMatcher($state1))
            ->andReturn(false)
            ->getMock()
            ->shouldReceive('ruleMatches')
            ->once()
            ->with($pathInfo, $rules[1], new EqualsMatcher($state2))
            ->andReturn(true)
            ->getMock()
            ->shouldReceive('ruleMatches')
            ->once()
            ->with($pathInfo, $rules[2], new EqualsMatcher($state3))
            ->andReturn(true)
            ->getMock()
        ;
        $this->checker
            ->shouldReceive('checkPredicate')
            ->once()
            ->with(
                $conditions2[0]->getPredicate(),
                $conditions2[0]->getSubject(),
                $conditions2[0]->getFlags(),
                new EqualsMatcher($state2)
            )
            ->andReturn(false)
            ->getMock()
            ->shouldReceive('checkPredicate')
            ->once()
            ->with(
                $conditions3[0]->getPredicate(),
                $conditions3[0]->getSubject(),
                $conditions3[0]->getFlags(),
                new EqualsMatcher($state3)
            )
            ->andReturn(false)
            ->getMock()
            ->shouldReceive('checkPredicate')
            ->once()
            ->with(
                $conditions3[1]->getPredicate(),
                $conditions3[1]->getSubject(),
                $conditions3[1]->getFlags(),
                new EqualsMatcher($state3)
            )
            ->andReturn(false)
            ->getMock()
        ;
        $this->assertEquals(
            new Result($pathInfo),
            $this->rewriter->rewrite($pathInfo, $request, $configuration)
        );
    }
    
    public function testRewriteWhereRuleMatches()
    {
        $pathInfo = 'url';
        $formatted = 'formatted';
        $configuration = new Configuration();
        $configuration
            ->addRule(new Rule(
                new Directive('', Directive::TYPE_RULE, 'rtyrtrty', 'bvcbvbcv', array()),
                array()
            ))
            ->addRule(new Rule(
                new Directive('', Directive::TYPE_RULE, 'url', 'adsdas', array()),
                array(
                    new Directive('', Directive::TYPE_CONDITION, 'xxvxc', 'nvnvvb', array()),
                    new Directive('', Directive::TYPE_CONDITION, 'dsadas', 'jgjhghj', array()),
                )
            ))
            ->addRule(new Rule(
                new Directive('', Directive::TYPE_RULE, 'url', 'wwrewre', array()),
                array(
                    new Directive('', Directive::TYPE_CONDITION, 'cbcvcvb', 'xxzcxz', array()),
                    new Directive('', Directive::TYPE_CONDITION, 'ghghjghj', 'bbcvbv', array()),
                )
            ))
        ;
        $rules = $configuration->getRules();
        $conditions2 = $rules[1]->getConditions();
        $conditions3 = $rules[2]->getConditions();
        $request = new Request(array('key' => 'value'));
        $state1 = new MatchState($rules[0], $pathInfo, $request);
        $state2 = new MatchState($rules[1], $pathInfo, $request);
        $state3 = new MatchState($rules[2], $pathInfo, $request);
        $this->matcher
            ->shouldReceive('ruleMatches')
            ->once()
            ->with($pathInfo, $rules[0], new EqualsMatcher($state1))
            ->andReturn(false)
            ->getMock()
            ->shouldReceive('ruleMatches')
            ->once()
            ->with($pathInfo, $rules[1], new EqualsMatcher($state2))
            ->andReturn(true)
            ->getMock()
            ->shouldReceive('ruleMatches')
            ->once()
            ->with($pathInfo, $rules[2], new EqualsMatcher($state3))
            ->andReturn(true)
            ->getMock()
        ;
        $this->checker
            ->shouldReceive('checkPredicate')
            ->once()
            ->with(
                $conditions2[0]->getPredicate(),
                $conditions2[0]->getSubject(),
                $conditions2[0]->getFlags(),
                new EqualsMatcher($state2)
            )
            ->andReturn(false)
            ->getMock()
            ->shouldReceive('checkPredicate')
            ->once()
            ->with(
                $conditions3[0]->getPredicate(),
                $conditions3[0]->getSubject(),
                $conditions3[0]->getFlags(),
                new EqualsMatcher($state3)
            )
            ->andReturn(true)
            ->getMock()
            ->shouldReceive('checkPredicate')
            ->once()
            ->with(
                $conditions3[1]->getPredicate(),
                $conditions3[1]->getSubject(),
                $conditions3[1]->getFlags(),
                new EqualsMatcher($state3)
            )
            ->andReturn(true)
            ->getMock()
        ;
        $this->formatter
            ->shouldReceive('format')
            ->once()
            ->with('wwrewre', new EqualsMatcher($state3))
            ->andReturn($formatted)
        ;
        $this->assertEquals(
            new Result($formatted, false, $rules[2]),
            $this->rewriter->rewrite($pathInfo, $request, $configuration)
        );
    }
    
    public function testRewriteWhereRuleMatchesBecauseConditionHasOr()
    {
        $pathInfo = 'url';
        $formatted = 'formatted';
        $configuration = new Configuration();
        $configuration
            ->addRule(new Rule(
                new Directive('', Directive::TYPE_RULE, 'rtyrtrty', 'bvcbvbcv', array()),
                array()
            ))
            ->addRule(new Rule(
                new Directive('', Directive::TYPE_RULE, 'url', 'adsdas', array()),
                array(
                    new Directive('', Directive::TYPE_CONDITION, 'xxvxc', 'nvnvvb', array()),
                    new Directive('', Directive::TYPE_CONDITION, 'dsadas', 'jgjhghj', array()),
                )
            ))
            ->addRule(new Rule(
                new Directive('', Directive::TYPE_RULE, 'url', 'wwrewre', array()),
                array(
                    new Directive('', Directive::TYPE_CONDITION, 'cbcvcvb', 'xxzcxz', array('OR' => true)),
                    new Directive('', Directive::TYPE_CONDITION, 'ghghjghj', 'bbcvbv', array()),
                )
            ))
        ;
        $rules = $configuration->getRules();
        $conditions2 = $rules[1]->getConditions();
        $conditions3 = $rules[2]->getConditions();
        $request = new Request(array('key' => 'value'));
        $state1 = new MatchState($rules[0], $pathInfo, $request);
        $state2 = new MatchState($rules[1], $pathInfo, $request);
        $state3 = new MatchState($rules[2], $pathInfo, $request);
        $this->matcher
            ->shouldReceive('ruleMatches')
            ->once()
            ->with($pathInfo, $rules[0], new EqualsMatcher($state1))
            ->andReturn(false)
            ->getMock()
            ->shouldReceive('ruleMatches')
            ->once()
            ->with($pathInfo, $rules[1], new EqualsMatcher($state2))
            ->andReturn(true)
            ->getMock()
            ->shouldReceive('ruleMatches')
            ->once()
            ->with($pathInfo, $rules[2], new EqualsMatcher($state3))
            ->andReturn(true)
            ->getMock()
        ;
        $this->checker
            ->shouldReceive('checkPredicate')
            ->once()
            ->with(
                $conditions2[0]->getPredicate(),
                $conditions2[0]->getSubject(),
                $conditions2[0]->getFlags(),
                new EqualsMatcher($state2)
            )
            ->andReturn(false)
            ->getMock()
            ->shouldReceive('checkPredicate')
            ->once()
            ->with(
                $conditions3[0]->getPredicate(),
                $conditions3[0]->getSubject(),
                $conditions3[0]->getFlags(),
                new EqualsMatcher($state3)
            )
            ->andReturn(true)
            ->getMock()
        ;
        $this->formatter
            ->shouldReceive('format')
            ->once()
            ->with('wwrewre', new EqualsMatcher($state3))
            ->andReturn($formatted)
        ;
        $this->assertEquals(
            new Result($formatted, false, $rules[2]),
            $this->rewriter->rewrite($pathInfo, $request, $configuration)
        );
    }
}
