<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/13/15
 * Time: 1:39 PM
 */

namespace Giftcards\ModRewrite\Condition;


use Giftcards\ModRewrite\MatchState;

interface PredicateCheckerInterface
{
    public function supports($predicate);
    public function checkPredicate(
        $predicate,
        $subject,
        array $flags,
        MatchState $matchState
    );
}