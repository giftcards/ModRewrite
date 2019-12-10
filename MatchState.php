<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/16/15
 * Time: 9:43 PM
 */

namespace Giftcards\ModRewrite;


use Giftcards\ModRewrite\Compiler\Rule;
use Symfony\Component\HttpFoundation\Request;

class MatchState
{
    const MATCH_REFERENCE_TYPE_REWRITE = 'rewrite';
    const MATCH_REFERENCE_TYPE_CONDITION = 'condition';
    
    protected $pathInfo;
    protected $rule;
    protected $request;
    protected $matchReferences = [];

    public function __construct(Rule $rule, $pathInfo, Request $request)
    {
        $this->pathInfo = $pathInfo;
        $this->rule = $rule;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getPathInfo()
    {
        return $this->pathInfo;
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function addMatchReferences(array $matchReferences, $type)
    {
        if (!isset($this->matchReferences[$type])) {
            
            $this->matchReferences[$type] = [];
        }
        
        $this->matchReferences[$type] = array_merge($this->matchReferences[$type], $matchReferences);
        return $this;
    }

    public function clearMatchReferences($type)
    {
        $this->matchReferences[$type] = [];
        return $this;
    }

    public function getMatchReference($key, $type)
    {
        return isset($this->matchReferences[$type][$key]) ? $this->matchReferences[$type][$key] : '';
    }

    public function getMatchReferences($type)
    {
        return isset($this->matchReferences[$type]) ? $this->matchReferences[$type] : [];
    }
}