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
    protected $redirect;
    protected $matchedRule;

    public function __construct($url, $redirect = false, Rule $matchedRule = null)
    {
        $this->url = $url;
        $this->redirect = $redirect;
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
     * @return mixed
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @return Rule
     */
    public function getMatchedRule()
    {
        return $this->matchedRule;
    }
}