<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/13/15
 * Time: 1:55 PM
 */

namespace Giftcards\ModRewrite\Condition;


use Giftcards\ModRewrite\MatchState;

class ExistencePredicateChecker implements PredicateCheckerInterface
{
    protected $dirPrefix;

    public function __construct($dirPrefix)
    {
        $this->dirPrefix = $dirPrefix;
    }

    public function supports($predicate)
    {
        return in_array($predicate, array('-d', '-f'));
    }

    public function checkPredicate(
        $predicate,
        $subject,
        array $flags,
        MatchState $matchState
    ) {
        if ($predicate == '-d') {
            
            return is_dir($this->dirPrefix.DIRECTORY_SEPARATOR.$subject);
        }
        
        if ($predicate == '-f') {
            
            return is_file($this->dirPrefix.DIRECTORY_SEPARATOR.$subject);
        }
    }
}