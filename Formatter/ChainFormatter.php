<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/13/15
 * Time: 1:41 PM
 */

namespace Giftcards\ModRewrite\Formatter;

use Giftcards\ModRewrite\MatchState;

class ChainFormatter implements FormatterInterface
{
    /** @var FormatterInterface[] */
    protected $formatter = [];

    public function add(FormatterInterface $source)
    {
        $this->formatter[] = $source;
        return $this;
    }

    public function format(
        $value,
        MatchState $matchState
    ) {
        foreach ($this->formatter as $formatter) {
            $value = $formatter->format(
                $value,
                $matchState
            );
        }
        
        return $value;
    }
}
