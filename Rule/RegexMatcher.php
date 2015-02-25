<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 6:40 PM
 */

namespace Giftcards\ModRewrite\Rule;


use Giftcards\ModRewrite\Compiler\Rule;
use Giftcards\ModRewrite\MatchState;

class RegexMatcher implements MatcherInterface
{
    public function ruleMatches($pathInfo, Rule $rule, MatchState $matchState)
    {
        $regex = sprintf(
            '#%s#',
            str_replace('#', '\#', $rule->getRewrite()->getSubject())
        );

        if (preg_match($regex, $pathInfo, $matches)) {

            $matchState->addMatchReferences(
                $matches,
                MatchState::MATCH_REFERENCE_TYPE_REWRITE
            );

            return true;
        }
        
        return false;
    }
}