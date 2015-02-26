<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/13/15
 * Time: 1:50 PM
 */

namespace Giftcards\ModRewrite\Condition;


use Giftcards\ModRewrite\MatchState;

class RegexPredicateChecker implements PredicateCheckerInterface
{
    public function supports($predicate)
    {
        return true;
    }

    public function checkPredicate(
        $predicate,
        $subject,
        array $flags,
        MatchState $matchState
    ) {
        $regex = sprintf('#%s#', str_replace('#', '\#', $predicate));
        
        if (!empty($flags['NC']) || !empty($flags['nocase'])) {
            
            $regex .= 'i';
        }
        
        if ($matched = (bool)preg_match($regex, $subject, $matches)) {

            $matchState
                ->clearMatchReferences(MatchState::MATCH_REFERENCE_TYPE_CONDITION)
                ->addMatchReferences($matches, MatchState::MATCH_REFERENCE_TYPE_CONDITION);
        }

        return $matched;
    }
}