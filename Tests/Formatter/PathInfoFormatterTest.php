<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/25/15
 * Time: 8:43 PM
 */

namespace Giftcards\ModRewrite\Tests\Formatter;


use Giftcards\ModRewrite\Formatter\PathInfoFormatter;
use Giftcards\ModRewrite\MatchState;
use Giftcards\ModRewrite\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class PathInfoFormatterTest extends TestCase
{
    /** @var  PathInfoFormatter */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new PathInfoFormatter();
    }

    public function testFormat()
    {
        $pathInfo = 'path';
        $state = new MatchState(\Mockery::mock('Giftcards\ModRewrite\Compiler\Rule'), $pathInfo, new Request());
        $this->assertEquals('path', $this->formatter->format('-', $state));
        $this->assertEquals('fsdfdsfsd', $this->formatter->format('fsdfdsfsd', $state));
    }
}
