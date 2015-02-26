<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/13/15
 * Time: 11:12 AM
 */

namespace Giftcards\ModRewrite;


use Giftcards\ModRewrite\Condition\PredicateCheckerInterface;
use Giftcards\ModRewrite\Formatter\FormatterInterface;
use Giftcards\ModRewrite\Compiler\Configuration;
use Giftcards\ModRewrite\Rule\MatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class Rewriter
{
    protected $formatter;
    protected $ruleMatcher;
    protected $conditionPredicateChecker;

    public function __construct(
        FormatterInterface $formatter,
        MatcherInterface $ruleMatcher,
        PredicateCheckerInterface $conditionPredicateChecker
    ) {
        $this->formatter = $formatter;
        $this->ruleMatcher = $ruleMatcher;
        $this->conditionPredicateChecker = $conditionPredicateChecker;
    }

    public function rewrite($pathInfo, Request $request, Configuration $engineConfiguration)
    {
        foreach ($engineConfiguration->getRules() as $rule) {

            $matchState = new MatchState($rule, $pathInfo, $request);

            if (
                $this->ruleMatcher->ruleMatches($pathInfo, $matchState->getRule(), $matchState)
                && $this->passesConditions($matchState)
            ) {
                return new Result(
                    $this->formatter->format(
                        $matchState->getRule()->getRewrite()->getPredicate(),
                        $matchState
                    ),
                    $rule
                );
            }
        }

        return new Result($pathInfo);
    }

    /**
     * @param MatchState $matchState
     * @return bool
     */
    protected function passesConditions(MatchState $matchState)
    {
        foreach ($matchState->getRule()->getConditions() as $condition) {

            $flags = $condition->getFlags();
            $passes = $this->conditionPredicateChecker->checkPredicate(
                $condition->getPredicate(),
                $condition->getSubject(),
                $flags,
                $matchState
            );

            if (!$passes && empty($flags['OR'])) {

                return false;
            }

            if ($passes && !empty($flags['OR'])) {

                return true;
            }
        }

        return true;
    }
}