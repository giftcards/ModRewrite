<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 7:18 PM
 */

namespace Giftcards\ModRewrite\Tests\Formatter;


use Giftcards\ModRewrite\Formatter\ChainFormatter;
use Mockery;
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;

class ChainFormatterTest extends AbstractExtendableTestCase
{
    /** @var  ChainFormatter */
    protected $formatter;

    public function setUp() :void
    {
        $this->formatter = new ChainFormatter();
    }

    public function testFormat()
    {
        $value = $this->getFaker()->word;
        $formatted1 = $this->getFaker()->word;
        $formatted2 = $this->getFaker()->word;
        $formatted3 = $this->getFaker()->word;
        $state = Mockery::mock('Giftcards\ModRewrite\MatchState');
        $formatter1 = Mockery::mock('Giftcards\ModRewrite\Formatter\FormatterInterface')
            ->shouldReceive('format')
            ->once()
            ->with($value, $state)
            ->andReturn($formatted1)
            ->getMock()
        ;
        $formatter2 = Mockery::mock('Giftcards\ModRewrite\Formatter\FormatterInterface')
            ->shouldReceive('format')
            ->once()
            ->with($formatted1, $state)
            ->andReturn($formatted2)
            ->getMock()
        ;
        $formatter3 = Mockery::mock('Giftcards\ModRewrite\Formatter\FormatterInterface')
            ->shouldReceive('format')
            ->once()
            ->with($formatted2, $state)
            ->andReturn($formatted3)
            ->getMock()
        ;
        $this->formatter
            ->add($formatter1)
            ->add($formatter2)
            ->add($formatter3)
        ;
        $this->assertEquals($formatted3, $this->formatter->format($value, $state));
    }
}
