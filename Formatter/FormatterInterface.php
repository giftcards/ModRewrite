<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/16/15
 * Time: 4:17 PM
 */

namespace Giftcards\ModRewrite\Formatter;


use Giftcards\ModRewrite\MatchState;

interface FormatterInterface
{
    public function format(
        $value,
        MatchState $matchState
    );
}