<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 3:53 PM
 */

namespace Giftcards\ModRewrite\Condition;


use Giftcards\ModRewrite\Formatter\FormatterInterface;
use Giftcards\ModRewrite\MatchState;

class FormattingPredicateChecker implements PredicateCheckerInterface
{
    protected $formatter;
    protected $innerChecker;

    public function __construct(FormatterInterface $formatter, PredicateCheckerInterface $innerChecker)
    {
        $this->formatter = $formatter;
        $this->innerChecker = $innerChecker;
    }

    public function supports($predicate)
    {
        return $this->innerChecker->supports($predicate);
    }

    public function checkPredicate(
        $predicate,
        $subject,
        array $flags,
        MatchState $matchState
    ) {
        return $this->innerChecker->checkPredicate(
            $this->formatter->format(
                $predicate,
                $matchState
            ),
            $this->formatter->format(
                $subject,
                $matchState
            ),
            $flags,
            $matchState
        );
    }
}