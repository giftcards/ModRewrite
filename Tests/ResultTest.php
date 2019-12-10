<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 5:35 PM
 */

namespace Giftcards\ModRewrite\Tests;

use Giftcards\ModRewrite\Result;
use Mockery;
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;

class ResultTest extends AbstractExtendableTestCase
{
    public function testGetters()
    {
        $url = $this->getFaker()->url;
        $result = new Result($url);
        $this->assertSame($url, $result->getUrl());
        $this->assertNull($result->getMatchedRule());
        $rule = Mockery::mock('Giftcards\ModRewrite\Compiler\Rule');
        $result = new Result($url, $rule);
        $this->assertSame($url, $result->getUrl());
        $this->assertSame($rule, $result->getMatchedRule());
    }
}
