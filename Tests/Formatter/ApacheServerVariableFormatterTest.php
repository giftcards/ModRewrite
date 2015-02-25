<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 7:26 PM
 */

namespace Giftcards\ModRewrite\Tests\Formatter;


use Giftcards\ModRewrite\Formatter\ApacheServerVariableFormatter;
use Giftcards\ModRewrite\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class ApacheServerVariableFormatterTest extends TestCase
{
    /** @var  ApacheServerVariableFormatter */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new ApacheServerVariableFormatter();
    }

    public function testFormatWhereVariableNotInList()
    {
        $request = new Request();
        $state = \Mockery::mock('Giftcards\ModRewrite\MatchState')
            ->shouldReceive('getRequest')
            ->andReturn($request)
            ->getMock()
        ;
        $value = 'sdfsdf%{not_in_array}dfggdf';
        $this->assertEquals($value ,$this->formatter->format($value, $state));
    }

    public function testFormatWhereVariableIsQueryString()
    {
        $request = new Request(array('key' => 'value', 'key2' => 'value2'));
        $state = \Mockery::mock('Giftcards\ModRewrite\MatchState')
            ->shouldReceive('getRequest')
            ->andReturn($request)
            ->getMock()
        ;
        $value = 'sdfsdf%{QUERY_STRING}dfggdf';
        $this->assertEquals('sdfsdfkey=value&key2=value2dfggdf' ,$this->formatter->format($value, $state));
    }

    public function testFormatWhereVariableIsRequestUri()
    {
        $state = \Mockery::mock('Giftcards\ModRewrite\MatchState')
            ->shouldReceive('getPathInfo')
            ->andReturn('path!')
            ->getMock()
        ;
        $value = 'sdfsdf%{REQUEST_URI}dfggdf';
        $this->assertEquals('sdfsdfpath!dfggdf' ,$this->formatter->format($value, $state));
    }

    public function testFormatWhereVariableIsRequestFilename()
    {
        $state = \Mockery::mock('Giftcards\ModRewrite\MatchState')
            ->shouldReceive('getPathInfo')
            ->andReturn('path!')
            ->getMock()
        ;
        $value = 'sdfsdf%{REQUEST_FILENAME}dfggdf';
        $this->assertEquals('sdfsdfpath!dfggdf' ,$this->formatter->format($value, $state));
    }

    public function testFormatWhereVariableIsHttpHost()
    {
        $request = new Request(array(), array(), array(), array(), array(), array('HTTP_HOST' => 'big_host'));
        $state = \Mockery::mock('Giftcards\ModRewrite\MatchState')
            ->shouldReceive('getRequest')
            ->andReturn($request)
            ->getMock()
        ;
        $value = 'sdfsdf%{HTTP_HOST}dfggdf';
        $this->assertEquals('sdfsdfbig_hostdfggdf' ,$this->formatter->format($value, $state));
    }
}
