<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 5:25 PM
 */

namespace Giftcards\ModRewrite\Tests;


use Giftcards\ModRewrite\MatchState;
use Mockery;
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;
use Symfony\Component\HttpFoundation\Request;

class MatchStateTest extends AbstractExtendableTestCase
{
    public function testGettersSetters()
    {
        $rule = Mockery::mock('Giftcards\ModRewrite\Compiler\Rule');
        $pathinfo = $this->getFaker()->word;
        $request = new Request();
        $state = new MatchState($rule, $pathinfo, $request);
        $this->assertSame($rule, $state->getRule());
        $this->assertSame($pathinfo, $state->getPathInfo());
        $this->assertSame($request, $state->getRequest());
        $this->assertSame($state, $state->addMatchReferences(['ref1', 'ref2'], MatchState::MATCH_REFERENCE_TYPE_REWRITE));
        $this->assertSame($state, $state->addMatchReferences(['ref3', 'ref4', 'ref5'], MatchState::MATCH_REFERENCE_TYPE_CONDITION));
        $this->assertEquals('', $state->getMatchReference(4, MatchState::MATCH_REFERENCE_TYPE_REWRITE));    
        $this->assertEquals('', $state->getMatchReference(3, MatchState::MATCH_REFERENCE_TYPE_CONDITION));    
        $this->assertEquals('', $state->getMatchReference(2, MatchState::MATCH_REFERENCE_TYPE_REWRITE));
        $this->assertEquals('ref2', $state->getMatchReference(1, MatchState::MATCH_REFERENCE_TYPE_REWRITE));
        $this->assertEquals('ref4', $state->getMatchReference(1, MatchState::MATCH_REFERENCE_TYPE_CONDITION));
        $this->assertEquals([], $state->getMatchReferences('bla'));
        $this->assertEquals(['ref1', 'ref2'], $state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_REWRITE));
        $this->assertEquals(['ref3', 'ref4', 'ref5'], $state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_CONDITION));
        $this->assertSame($state, $state->clearMatchReferences('bla'));
        $this->assertEquals($state, $state->clearMatchReferences(MatchState::MATCH_REFERENCE_TYPE_REWRITE));
        $this->assertEquals([], $state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_REWRITE));
        $this->assertEquals(['ref3', 'ref4', 'ref5'], $state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_CONDITION));
        $state->addMatchReferences([1 => 'ref6'], MatchState::MATCH_REFERENCE_TYPE_CONDITION);
        $this->assertEquals(['ref3', 'ref4', 'ref5', 'ref6'], $state->getMatchReferences(MatchState::MATCH_REFERENCE_TYPE_CONDITION));
    }
}
