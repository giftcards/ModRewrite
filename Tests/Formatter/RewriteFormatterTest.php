<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 10:25 PM
 */

namespace Giftcards\ModRewrite\Tests\Formatter;


use Giftcards\ModRewrite\Formatter\RewriteFormatter;
use Giftcards\ModRewrite\MatchState;
use Mockery;
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;
use Symfony\Component\HttpFoundation\Request;

class RewriteFormatterTest extends AbstractExtendableTestCase
{
    /** @var  RewriteFormatter */
    protected $formatter;

    public function setUp() :void
    {
        $this->formatter = new RewriteFormatter();
    }

    public function testFormat()
    {
        $value = 'dsffsd$1ffsd$2';
        $state = new MatchState(Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'), '', new Request());
        $state->addMatchReferences(['', 'hello'], MatchState::MATCH_REFERENCE_TYPE_REWRITE);
        $this->assertEquals('dsffsdhelloffsd', $this->formatter->format($value, $state));
    }
}
