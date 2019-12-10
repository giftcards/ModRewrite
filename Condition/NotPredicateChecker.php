<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/16/15
 * Time: 6:48 PM
 */

namespace Giftcards\ModRewrite\Condition;


use Giftcards\ModRewrite\MatchState;

class NotPredicateChecker implements PredicateCheckerInterface
{
    protected $innerChecker;

    public function __construct(PredicateCheckerInterface $innerChecker)
    {
        $this->innerChecker = $innerChecker;
    }

    public function supports($predicate)
    {
        list($predicate) = $this->splitOutNot($predicate);
        return $this->innerChecker->supports($predicate);
    }

    public function checkPredicate(
        $predicate,
        $subject,
        array $flags,
        MatchState $matchState
    ) {
        list($predicate, $not) = $this->splitOutNot($predicate);

        $matched = $this->innerChecker->checkPredicate(
            $predicate,
            $subject,
            $flags,
            $matchState
        );

        return (!$not && $matched) || ($not && !$matched);
    }

    protected function splitOutNot($predicate)
    {
        $not = false;
        
        if (stripos($predicate, '!') === 0) {
            
            $not = true;
            $predicate = substr($predicate, 1);
        }
        
        return [$predicate, $not];
    }
}