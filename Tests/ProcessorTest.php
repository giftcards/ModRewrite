<?php
/**
 * Created by PhpStorm.
 * User: ydera00
 * Date: 3/14/17
 * Time: 5:15 PM
 */

namespace Giftcards\ModRewrite\Tests;

use Giftcards\ModRewrite\Compiler\Directive;
use Giftcards\ModRewrite\Compiler\Rule;
use Giftcards\ModRewrite\Processor;
use Giftcards\ModRewrite\Result;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProcessorTest extends TestCase
{
    /** @var  Processor */
    protected $processor;

    public function setUp()
    {
        $this->processor = new Processor();
    }

    public function testProcessWithRedirect()
    {
        $url = $this->getFaker()->unique()->url;
        $statusCode = 301;
        $this->assertEquals(
            new RedirectResponse($url, $statusCode),
            $this->processor->process(new Request(), new Result(
                $url,
                new Rule(new Directive('', '', '', '', array('redirect' => $statusCode)), array())
            ))
        );
        $this->assertEquals(
            new RedirectResponse($url, $statusCode),
            $this->processor->process(new Request(), new Result(
                $url,
                new Rule(new Directive('', '', '', '', array('R' => $statusCode)), array())
            ))
        );
    }

    public function testProcessWithGone()
    {
        $this->assertEquals(
            new Response('', 410),
            $this->processor->process(new Request(), new Result(
                $this->getFaker()->unique()->url,
                new Rule(new Directive('', '', '', '', array('gone' => true)), array())
            ))
        );
        $this->assertEquals(
            new Response('', 410),
            $this->processor->process(new Request(), new Result(
                $this->getFaker()->unique()->url,
                new Rule(new Directive('', '', '', '', array('G' => true)), array())
            ))
        );
    }

    public function testProcessWithForbidden()
    {
        $this->assertEquals(
            new Response('', 403),
            $this->processor->process(new Request(), new Result(
                $this->getFaker()->unique()->url,
                new Rule(new Directive('', '', '', '', array('forbidden' => true)), array())
            ))
        );
        $this->assertEquals(
            new Response('', 403),
            $this->processor->process(new Request(), new Result(
                $this->getFaker()->unique()->url,
                new Rule(new Directive('', '', '', '', array('F' => true)), array())
            ))
        );
    }

    public function testProcessWithoutQueryStringAppendButQueryEmpty()
    {
        $url = $this->getFaker()->unique()->url;
        var_dump($url);
        $request = new Request(
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => new UploadedFile(__FILE__, '')),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            $this->getFaker()->unique()->sentence
        );
        $newRequest = $request->duplicate();
        $newRequest->server->set('REQUEST_URI', $url);
        $this->assertEquals(
            $newRequest,
            $this->processor->process($request, new Result(
                $url,
                new Rule(new Directive('', '', '', '', array()), array())
            ))
        );
    }

    public function testProcessWithoutQueryStringAppendAndQueryNotEmpty()
    {
        $queryKey = $this->getFaker()->unique()->word;
        $queryValue = $this->getFaker()->unique()->word;
        $noQueryUrl = $this->getFaker()->unique()->url;
        $url = sprintf(
            '%s?%s=%s',
            $noQueryUrl,
            $queryKey,
            $queryValue
        );
        var_dump($url);
        $request = new Request(
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => new UploadedFile(__FILE__, '')),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            $this->getFaker()->unique()->sentence
        );
        $newRequest = $request->duplicate(array($queryKey => $queryValue));
        $newRequest->server->set('REQUEST_URI', $noQueryUrl);
        $this->assertEquals(
            $newRequest,
            $this->processor->process($request, new Result(
                $url,
                new Rule(new Directive('', '', '', '', array()), array())
            ))
        );
    }

    public function testProcessWithQueryStringAppend()
    {
        $queryKey = $this->getFaker()->unique()->word;
        $queryValue = $this->getFaker()->unique()->word;
        $noQueryUrl = $this->getFaker()->unique()->url;
        $url = sprintf(
            '%s?%s=%s',
            $noQueryUrl,
            $queryKey,
            $queryValue
        );
        $oldQuery = array(
            $this->getFaker()->unique()->word => $this->getFaker()->unique()->word
        );
        $request = new Request(
            $oldQuery,
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            array($this->getFaker()->unique()->word => new UploadedFile(__FILE__, '')),
            array($this->getFaker()->unique()->word => $this->getFaker()->unique()->word),
            $this->getFaker()->unique()->sentence
        );
        $newRequest = $request->duplicate(array_merge(
            array($queryKey => $queryValue),
            $oldQuery
        ));
        $newRequest->server->set('REQUEST_URI', $noQueryUrl);
        $this->assertEquals(
            $newRequest,
            $this->processor->process($request, new Result(
                $url,
                new Rule(new Directive('', '', '', '', array('qsappend' => true)), array())
            ))
        );
        $this->assertEquals(
            $newRequest,
            $this->processor->process($request, new Result(
                $url,
                new Rule(new Directive('', '', '', '', array('QSA' => true)), array())
            ))
        );
    }
}
