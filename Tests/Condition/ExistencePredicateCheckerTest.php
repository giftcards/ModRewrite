<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 10:59 PM
 */

namespace Giftcards\ModRewrite\Tests\Condition;


use Giftcards\ModRewrite\Condition\ExistencePredicateChecker;
use Giftcards\ModRewrite\MatchState;
use Giftcards\ModRewrite\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class ExistencePredicateCheckerTest extends TestCase
{
    /** @var  ExistencePredicateChecker */
    protected $checker;

    public function setUp()
    {
        $this->checker = new ExistencePredicateChecker(
            __DIR__.'/../Fixtures'
        );
    }

    public function testSupports()
    {
        $this->assertFalse($this->checker->supports('fsdsdf'));
        $this->assertTrue($this->checker->supports('-f'));
        $this->assertTrue($this->checker->supports('-d'));
    }

    public function testCheckPredicate()
    {
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
        $this->assertTrue($this->checker->checkPredicate('-d', 'existing_dir', $flags, $state));
        $this->assertFalse($this->checker->checkPredicate('-f', 'existing_dir', $flags, $state));
        $this->assertTrue($this->checker->checkPredicate('-f', 'existing_file.txt', $flags, $state));
        $this->assertFalse($this->checker->checkPredicate('-d', 'existing_file.txt', $flags, $state));
        $this->assertFalse($this->checker->checkPredicate('-f', 'non_existing_file.txt', $flags, $state));
        $this->assertFalse($this->checker->checkPredicate('-d', 'non_existing_file.txt', $flags, $state));
        $this->assertFalse($this->checker->checkPredicate('-f', 'non_existing_dir', $flags, $state));
        $this->assertFalse($this->checker->checkPredicate('-d', 'non_existing_dir', $flags, $state));
    }
}
