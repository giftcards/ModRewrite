<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/16/15
 * Time: 9:40 PM
 */

namespace Giftcards\ModRewrite\Formatter;


use Giftcards\ModRewrite\MatchState;

class RewriteFormatter implements FormatterInterface
{
    public function format(
        $value,
        MatchState $matchState
    ) {
        return preg_replace_callback('/\$(\d+)/', function(array $matches) use ($matchState)
        {
            return $matchState->getMatchReference(
                $matches[1],
                MatchState::MATCH_REFERENCE_TYPE_REWRITE
            );
        }, $value);
    }
}