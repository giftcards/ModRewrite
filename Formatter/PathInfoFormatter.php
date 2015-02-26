<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/25/15
 * Time: 8:42 PM
 */

namespace Giftcards\ModRewrite\Formatter;


use Giftcards\ModRewrite\MatchState;

class PathInfoFormatter implements FormatterInterface
{
    public function format(
        $value,
        MatchState $matchState
    ) {
        if ($value == '-') {
            
            return $matchState->getPathInfo();
        }
        
        return $value;
    }
}