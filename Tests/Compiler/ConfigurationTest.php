<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 5:17 PM
 */

namespace Giftcards\ModRewrite\Tests\Compiler;


use Giftcards\ModRewrite\Compiler\Configuration;
use Mockery;
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;

class ConfigurationTest extends AbstractExtendableTestCase
{
    public function testGettersSetters()
    {
        $configuration = new Configuration();
        $this->assertFalse($configuration->isEngineOn());
        $this->assertSame($configuration, $configuration->setEngineOn($on = $this->getFaker()->boolean));
        $this->assertSame($on, $configuration->isEngineOn());
        $this->assertEquals([], $configuration->getRules());
        $rules = [
            Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'),
            Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'),
        ];

        foreach ($rules as $rule) {

            $this->assertSame($configuration, $configuration->addRule($rule));
        }

        $this->assertEquals($rules, $configuration->getRules());
        
        $options = [
            Mockery::mock('Giftcards\ModRewrite\Compiler\Directive'),
            Mockery::mock('Giftcards\ModRewrite\Compiler\Directive'),
        ];

        foreach ($options as $option) {

            $this->assertSame($configuration, $configuration->addOption($option));
        }

        $this->assertEquals($options, $configuration->getOptions());        
        
        $maps = [
            Mockery::mock('Giftcards\ModRewrite\Compiler\Directive'),
            Mockery::mock('Giftcards\ModRewrite\Compiler\Directive'),
        ];
        
        foreach ($maps as $map) {

            $this->assertSame($configuration, $configuration->addMap($map));
        }

        $this->assertEquals($maps, $configuration->getMaps());
    }
}
