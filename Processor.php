<?php
/**
 * Created by PhpStorm.
 * User: ydera00
 * Date: 3/14/17
 * Time: 12:34 PM
 */

namespace Giftcards\ModRewrite;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Processor
{
    public function process(Request $request, Result $result)
    {
        $flags = $result->getMatchedRule()->getRewrite()->getFlags();

        if (!empty($flags['redirect']) || !empty($flags['R'])) {
            $statusCode = !empty($flags['redirect']) ? $flags['redirect'] : $flags['R'];
            return new RedirectResponse($result->getUrl(), $statusCode);
        }

        if (!empty($flags['gone']) || !empty($flags['G'])) {
            return new Response('', 410);
        }

        if (!empty($flags['forbidden']) || !empty($flags['F'])) {
            return new Response('', 403);
        }

        $pieces = explode('?', $result->getUrl(), 2);
        $pathinfo = $pieces[0];
        parse_str(isset($pieces[1]) ? $pieces[1] : '', $query);

        $flags = $result->getMatchedRule()->getRewrite()->getFlags();

        if (empty($query) || !empty($flags['QSA']) || !empty($flags['qsappend'])) {
            $query = array_merge($query, $request->query->all());
        }

        $newRequest = $request->duplicate($query);
        $newRequest->server->set('REQUEST_URI', $pathinfo);
        return $newRequest;
    }
}
