<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 10:29 PM
 */

namespace Giftcards\ModRewrite\Tests\Condition;


use Giftcards\ModRewrite\Condition\ChainPredicateChecker;
use Giftcards\ModRewrite\MatchState;
use Giftcards\ModRewrite\Tests\TestCase;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Request;

class ChainPredicateCheckerTest extends TestCase
{
    /** @var  ChainPredicateChecker */
    protected $chainChecker;
    /** @var  MockInterface */
    protected $checker1;
    /** @var  MockInterface */
    protected $checker2;
    /** @var  MockInterface */
    protected $checker3;
    /** @var  MockInterface */
    protected $defaultChecker;

    public function setUp()
    {
        $this->chainChecker = new ChainPredicateChecker();
        $this->chainChecker
            ->add($this->checker1 = \Mockery::mock('Giftcards\ModRewrite\Condition\PredicateCheckerInterface'))
            ->add($this->checker2 = \Mockery::mock('Giftcards\ModRewrite\Condition\PredicateCheckerInterface'))
            ->add($this->checker3 = \Mockery::mock('Giftcards\ModRewrite\Condition\PredicateCheckerInterface'))
        ;
        $this->defaultChecker = \Mockery::mock('Giftcards\ModRewrite\Condition\PredicateCheckerInterface');
    }

    public function testSupports()
    {
        $predicate = $this->getFaker()->word;
        $this->checker1
            ->shouldReceive('supports')
            ->twice()
            ->with($predicate)
            ->andReturn(false, false)
        ;
        $this->checker2
            ->shouldReceive('supports')
            ->twice()
            ->with($predicate)
            ->andReturn(false, true)
        ;
        $this->checker3
            ->shouldReceive('supports')
            ->once()
            ->with($predicate)
            ->andReturn(false)
        ;
        $this->assertFalse($this->chainChecker->supports($predicate));
        $this->assertTrue($this->chainChecker->supports($predicate));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCheckPredicateWhereNoneSupportedAndNoDefault()
    {
        $predicate = $this->getFaker()->word;
        $subject = $this->getFaker()->word;
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
        $this->checker1
            ->shouldReceive('supports')
            ->once()
            ->with($predicate)
            ->andReturn(false)
        ;
        $this->checker2
            ->shouldReceive('supports')
            ->once()
            ->with($predicate)
            ->andReturn(false)
        ;
        $this->checker3
            ->shouldReceive('supports')
            ->once()
            ->with($predicate)
            ->andReturn(false)
        ;
        $this->chainChecker->checkPredicate($predicate, $subject, $flags, $state);
    }

    public function testCheckPredicateWhereNoneSupportedAndHasDefault()
    {
        $predicate = $this->getFaker()->word;
        $subject = $this->getFaker()->word;
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
        $this->checker1
            ->shouldReceive('supports')
            ->once()
            ->with($predicate)
            ->andReturn(false)
        ;
        $this->checker2
            ->shouldReceive('supports')
            ->once()
            ->with($predicate)
            ->andReturn(false)
        ;
        $this->checker3
            ->shouldReceive('supports')
            ->once()
            ->with($predicate)
            ->andReturn(false)
        ;
        $this->defaultChecker
            ->shouldReceive('checkPredicate')
            ->once()
            ->with($predicate, $subject, $flags, $state)
            ->andReturn($passes = $this->getFaker()->boolean)
        ;
        $this->assertSame($this->chainChecker, $this->chainChecker->setDefault($this->defaultChecker));
        $this->assertEquals(
            $passes,
            $this->chainChecker->checkPredicate($predicate, $subject, $flags, $state)
        );
    }

    public function testCheckPredicateWhereOneSupported()
    {
        $predicate = $this->getFaker()->word;
        $subject = $this->getFaker()->word;
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
        $this->checker1
            ->shouldReceive('supports')
            ->once()
            ->with($predicate)
            ->andReturn(false)
        ;
        $this->checker2
            ->shouldReceive('supports')
            ->once()
            ->with($predicate)
            ->andReturn(true)
            ->getMock()
            ->shouldReceive('checkPredicate')
            ->once()
            ->with($predicate, $subject, $flags, $state)
            ->andReturn($passes = $this->getFaker()->boolean)
        ;
        $this->assertEquals(
            $passes,
            $this->chainChecker->checkPredicate($predicate, $subject, $flags, $state)
        );
    }
}
