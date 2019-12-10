<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 5:22 PM
 */

namespace Giftcards\ModRewrite\Tests\Compiler;


use Giftcards\ModRewrite\Compiler\Rule;
use Mockery;
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;

class RuleTest extends AbstractExtendableTestCase
{
    public function testGettersSetters()
    {
        $rewrite = Mockery::mock('Giftcards\ModRewrite\Compiler\Directive');
        $conditions = [
            Mockery::mock('Giftcards\ModRewrite\Compiler\Directive'),
            Mockery::mock('Giftcards\ModRewrite\Compiler\Directive'),
        ];
        $rule = new Rule($rewrite, $conditions);
        $this->assertSame($rewrite, $rule->getRewrite());
        $this->assertSame($conditions, $rule->getConditions());
    }
}
