<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 10:53 PM
 */

namespace Giftcards\ModRewrite\Tests\Condition;


use Giftcards\ModRewrite\Condition\NotPredicateChecker;
use Giftcards\ModRewrite\MatchState;
use Mockery;
use Mockery\MockInterface;
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;
use Symfony\Component\HttpFoundation\Request;

class NotPredicateCheckerTest extends AbstractExtendableTestCase
{
    /** @var  NotPredicateChecker */
    protected $checker;
    /** @var  MockInterface */
    protected $innerChecker;

    public function setUp() :void
    {
        $this->checker = new NotPredicateChecker(
            $this->innerChecker = Mockery::mock('Giftcards\ModRewrite\Condition\PredicateCheckerInterface')
        );
    }

    public function testSupports()
    {
        $predicate = 'predicate';
        $this->innerChecker
            ->shouldReceive('supports')
            ->times(4)
            ->with($predicate)
            ->andReturn(true, false, true, false)
        ;
        $this->assertTrue($this->checker->supports($predicate));
        $this->assertFalse($this->checker->supports($predicate));
        $this->assertTrue($this->checker->supports('!'.$predicate));
        $this->assertFalse($this->checker->supports('!'.$predicate));
    }

    public function testCheckPredicate()
    {
        $predicate = $this->getFaker()->word;
        $subject = $this->getFaker()->word;
        $flags = [
            $this->getFaker()->word => $this->getFaker()->word,
            $this->getFaker()->word => true,
            $this->getFaker()->word => false,
        ];
        $pathInfo = $this->getFaker()->word;
        $state = new MatchState(
            Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'),
            $pathInfo,
            new Request()
        );
        $this->innerChecker
            ->shouldReceive('checkPredicate')
            ->times(4)
            ->with($predicate, $subject, $flags, $state)
            ->andReturn(true, false, true, false)
        ;
        $this->assertTrue($this->checker->checkPredicate($predicate, $subject, $flags, $state));
        $this->assertFalse($this->checker->checkPredicate($predicate, $subject, $flags, $state));
        $this->assertFalse($this->checker->checkPredicate('!'.$predicate, $subject, $flags, $state));
        $this->assertTrue($this->checker->checkPredicate('!'.$predicate, $subject, $flags, $state));
    }
}
