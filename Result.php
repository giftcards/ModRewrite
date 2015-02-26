<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/16/15
 * Time: 2:30 PM
 */

namespace Giftcards\ModRewrite;


use Giftcards\ModRewrite\Compiler\Rule;

class Result
{
    protected $url;
    protected $matchedRule;

    public function __construct($url, Rule $matchedRule = null)
    {
        $this->url = $url;
        $this->matchedRule = $matchedRule;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return Rule
     */
    public function getMatchedRule()
    {
        return $this->matchedRule;
    }
}