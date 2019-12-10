<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 7:37 PM
 */

namespace Giftcards\ModRewrite\Tests\Formatter;


use Giftcards\ModRewrite\Formatter\LastPassingConditionFormatter;
use Giftcards\ModRewrite\MatchState;
use Mockery;
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;
use Symfony\Component\HttpFoundation\Request;

class LastPassingConditionFormatterTest extends AbstractExtendableTestCase
{
    /** @var  LastPassingConditionFormatter */
    protected $formatter;

    public function setUp() :void
    {
        $this->formatter = new LastPassingConditionFormatter();
    }

    public function testFormat()
    {
        $value = 'dsffsd%1ffsd%2';
        $state = new MatchState(Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'), '', new Request());
        $state->addMatchReferences(['', 'hello'], MatchState::MATCH_REFERENCE_TYPE_CONDITION);
        $this->assertEquals('dsffsdhelloffsd', $this->formatter->format($value, $state));
    }
}
