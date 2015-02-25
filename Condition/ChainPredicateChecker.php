<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/13/15
 * Time: 1:41 PM
 */

namespace Giftcards\ModRewrite\Condition;


use Giftcards\ModRewrite\MatchState;

class ChainPredicateChecker implements PredicateCheckerInterface
{
    /** @var PredicateCheckerInterface[] */
    protected $checkers = array();
    /** @var  PredicateCheckerInterface|null */
    protected $default;

    public function add(PredicateCheckerInterface $checker)
    {
        $this->checkers[] = $checker;
        return $this;
    }

    public function setDefault(PredicateCheckerInterface $checker)
    {
        $this->default = $checker;
        return $this;
    }

    public function supports($predicate)
    {
        foreach ($this->checkers as $checker) {
            
            if ($checker->supports($predicate)) {
                
                return true;
            }
        }

        return false;
    }

    public function checkPredicate(
        $predicate,
        $subject,
        array $flags,
        MatchState $matchState
    ) {
        foreach ($this->checkers as $checker) {

            if ($checker->supports($predicate)) {

                return $checker->checkPredicate(
                    $predicate,
                    $subject,
                    $flags,
                    $matchState
                );
            }
        }
        
        if ($this->default) {
            
            return $this->default->checkPredicate(
                $predicate,
                $subject,
                $flags,
                $matchState
            );
        }

        throw new \RuntimeException('no predicate processor could process the predicate.');
    }
}