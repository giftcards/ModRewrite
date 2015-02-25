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

interface MatcherInterface
{
    public function ruleMatches($pathInfo, Rule $rule, MatchState $matchState);
}