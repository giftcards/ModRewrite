<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/25/15
 * Time: 3:03 PM
 */

namespace Giftcards\ModRewrite;


use Giftcards\ModRewrite\Condition\ChainPredicateChecker;
use Giftcards\ModRewrite\Condition\ExistencePredicateChecker;
use Giftcards\ModRewrite\Condition\FormattingPredicateChecker;
use Giftcards\ModRewrite\Condition\NotPredicateChecker;
use Giftcards\ModRewrite\Condition\PredicateCheckerInterface;
use Giftcards\ModRewrite\Condition\RegexPredicateChecker;
use Giftcards\ModRewrite\Formatter\ApacheServerVariableFormatter;
use Giftcards\ModRewrite\Formatter\ChainFormatter;
use Giftcards\ModRewrite\Formatter\FormatterInterface;
use Giftcards\ModRewrite\Formatter\LastPassingConditionFormatter;
use Giftcards\ModRewrite\Formatter\PathInfoFormatter;
use Giftcards\ModRewrite\Formatter\RewriteFormatter;
use Giftcards\ModRewrite\Rule\MatcherInterface;
use Giftcards\ModRewrite\Rule\RegexMatcher;

class RewriterBuilder
{
    protected $formatter;
    protected $ruleMatcher;
    protected $conditionPredicateChecker;
    protected $workingDir = '';

    public function create()
    {
        return new static();
    }

    public function build()
    {
        return new Rewriter(
            $this->getFormatter(),
            $this->getRuleMatcher(),
            $this->getConditionPredicateChecker()
        );
    }

    public function getFormatter()
    {
        if (!$this->formatter) {
            
            $this->formatter = $this->getDefaultFormatter();
        }
        
        return $this->formatter;
    }

    /**
     * @param mixed $formatter
     * @return $this
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRuleMatcher()
    {
        if (!$this->ruleMatcher) {
            
            $this->ruleMatcher = $this->getDefaultRuleMatcher();
        }
        
        return $this->ruleMatcher;
    }

    /**
     * @param mixed $ruleMatcher
     * @return $this
     */
    public function setRuleMatcher(MatcherInterface $ruleMatcher)
    {
        $this->ruleMatcher = $ruleMatcher;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConditionPredicateChecker()
    {
        if (!$this->conditionPredicateChecker) {
           
            $this->conditionPredicateChecker = $this->getDefaultConditionPredicateChecker();
        }
        
        return $this->conditionPredicateChecker;
    }

    /**
     * @param mixed $conditionPredicateChecker
     * @return $this
     */
    public function setConditionPredicateChecker(PredicateCheckerInterface $conditionPredicateChecker)
    {
        $this->conditionPredicateChecker = $conditionPredicateChecker;
        return $this;
    }

    protected function getDefaultFormatter()
    {
        $chain = new ChainFormatter();
        $chain
            ->add(new ApacheServerVariableFormatter())
            ->add(new LastPassingConditionFormatter())
            ->add(new RewriteFormatter())
            ->add(new PathInfoFormatter())
        ;
        return $chain;
    }

    protected function getDefaultRuleMatcher()
    {
        return new RegexMatcher();
    }

    protected function getDefaultConditionPredicateChecker()
    {
        $chain = new ChainPredicateChecker();
        $chain
            ->add(new ExistencePredicateChecker($this->workingDir))
            ->setDefault(new RegexPredicateChecker())
        ;
        return new FormattingPredicateChecker(
            $this->getFormatter(),
            new NotPredicateChecker($chain)
        );
    }
}