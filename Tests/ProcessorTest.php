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
use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProcessorTest extends AbstractExtendableTestCase
{
    /** @var  Processor */
    protected $processor;

    public function setUp() :void
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
                new Rule(new Directive('', '', '', '', ['redirect' => $statusCode]), [])
            ))
        );
        $this->assertEquals(
            new RedirectResponse($url, $statusCode),
            $this->processor->process(new Request(), new Result(
                $url,
                new Rule(new Directive('', '', '', '', ['R' => $statusCode]), [])
            ))
        );
    }

    public function testProcessWithGone()
    {
        $this->assertEquals(
            new Response('', 410),
            $this->processor->process(new Request(), new Result(
                $this->getFaker()->unique()->url,
                new Rule(new Directive('', '', '', '', ['gone' => true]), [])
            ))
        );
        $this->assertEquals(
            new Response('', 410),
            $this->processor->process(new Request(), new Result(
                $this->getFaker()->unique()->url,
                new Rule(new Directive('', '', '', '', ['G' => true]), [])
            ))
        );
    }

    public function testProcessWithForbidden()
    {
        $this->assertEquals(
            new Response('', 403),
            $this->processor->process(new Request(), new Result(
                $this->getFaker()->unique()->url,
                new Rule(new Directive('', '', '', '', ['forbidden' => true]), [])
            ))
        );
        $this->assertEquals(
            new Response('', 403),
            $this->processor->process(new Request(), new Result(
                $this->getFaker()->unique()->url,
                new Rule(new Directive('', '', '', '', ['F' => true]), [])
            ))
        );
    }

    public function testProcessWithoutQueryStringAppendButQueryEmpty()
    {
        $url = $this->getFaker()->unique()->url;
        $request = new Request(
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => new UploadedFile(__FILE__, '')],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            $this->getFaker()->unique()->sentence
        );
        $newRequest = $request->duplicate();
        $newRequest->server->set('REQUEST_URI', $url);
        $this->assertEquals(
            $newRequest,
            $this->processor->process($request, new Result(
                $url,
                new Rule(new Directive('', '', '', '', []), [])
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
        $request = new Request(
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => new UploadedFile(__FILE__, '')],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            $this->getFaker()->unique()->sentence
        );
        $newRequest = $request->duplicate([$queryKey => $queryValue]);
        $newRequest->server->set('REQUEST_URI', $noQueryUrl);
        $this->assertEquals(
            $newRequest,
            $this->processor->process($request, new Result(
                $url,
                new Rule(new Directive('', '', '', '', []), [])
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
        $oldQuery = [
            $this->getFaker()->unique()->word => $this->getFaker()->unique()->word
        ];
        $request = new Request(
            $oldQuery,
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            [$this->getFaker()->unique()->word => new UploadedFile(__FILE__, '')],
            [$this->getFaker()->unique()->word => $this->getFaker()->unique()->word],
            $this->getFaker()->unique()->sentence
        );
        $newRequest = $request->duplicate(array_merge(
            [$queryKey => $queryValue],
            $oldQuery
        ));
        $newRequest->server->set('REQUEST_URI', $noQueryUrl);
        $this->assertEquals(
            $newRequest,
            $this->processor->process($request, new Result(
                $url,
                new Rule(new Directive('', '', '', '', ['qsappend' => true]), [])
            ))
        );
        $this->assertEquals(
            $newRequest,
            $this->processor->process($request, new Result(
                $url,
                new Rule(new Directive('', '', '', '', ['QSA' => true]), [])
            ))
        );
    }
}
