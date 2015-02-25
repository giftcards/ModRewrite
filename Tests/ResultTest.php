<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 5:35 PM
 */

namespace Giftcards\ModRewrite\Tests;


use Giftcards\ModRewrite\Result;

class ResultTest extends TestCase
{
    public function testGetters()
    {
        $url = $this->getFaker()->url;
        $result = new Result($url);
        $this->assertSame($url, $result->getUrl());
        $this->assertFalse($result->getRedirect());
        $this->assertNull($result->getMatchedRule());
        $redirect = $this->getFaker()->randomNumber;
        $result = new Result($url, $redirect);
        $this->assertSame($url, $result->getUrl());
        $this->assertSame($redirect, $result->getRedirect());
        $this->assertNull($result->getMatchedRule());
        $rule = \Mockery::mock('Giftcards\ModRewrite\Compiler\Rule');
        $result = new Result($url, $redirect, $rule);
        $this->assertSame($url, $result->getUrl());
        $this->assertSame($redirect, $result->getRedirect());
        $this->assertSame($rule, $result->getMatchedRule());
    }
}
