<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 11:06 PM
 */

namespace Giftcards\ModRewrite\Condition;


use Giftcards\ModRewrite\MatchState;
use Giftcards\ModRewrite\Tests\TestCase;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Request;

class FormattingPredicateCheckerTest extends TestCase
{
    /** @var  FormattingPredicateChecker */
    protected $checker;
    /** @var  MockInterface */
    protected $formatter;
    /** @var  MockInterface */
    protected $innerChecker;

    public function setUp()
    {
        $this->checker = new FormattingPredicateChecker(
            $this->formatter = \Mockery::mock('Giftcards\ModRewrite\Formatter\FormatterInterface'),
            $this->innerChecker = \Mockery::mock('Giftcards\ModRewrite\Condition\PredicateCheckerInterface')
        );
    }

    public function testSupports()
    {
        $predicate = 'predicate';
        $this->innerChecker
            ->shouldReceive('supports')
            ->twice()
            ->with($predicate)
            ->andReturn(true, false)
        ;
        $this->assertTrue($this->checker->supports($predicate));
        $this->assertFalse($this->checker->supports($predicate));
    }
    public function testCheckPredicate()
    {
        $predicate = $this->getFaker()->word;
        $subject = $this->getFaker()->word;
        $formattedPredicate = $this->getFaker()->word;
        $formattedSubject = $this->getFaker()->word;
        $flags = array(
            $this->getFaker()->word => $this->getFaker()->word,
            $this->getFaker()->word => true,
            $this->getFaker()->word => false,
        );
        $pathInfo = $this->getFaker()->word;
        $state = new MatchState(
            \Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'),
            $pathInfo,
            new Request()
        );
        $this->formatter
            ->shouldReceive('format')
            ->once()
            ->with($predicate, $state)
            ->andReturn($formattedPredicate)
            ->getMock()
            ->shouldReceive('format')
            ->once()
            ->with($subject, $state)
            ->andReturn($formattedSubject)
            ->getMock()
        ;
        $this->innerChecker
            ->shouldReceive('checkPredicate')
            ->once()
            ->with(
                $formattedPredicate,
                $formattedSubject,
                $flags,
                $state
            )
            ->andReturn($passes = $this->getFaker()->boolean)
        ;
        $this->assertEquals(
            $passes,
            $this->checker->checkPredicate($predicate, $subject, $flags, $state)
        );
    }
}
