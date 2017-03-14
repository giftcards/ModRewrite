<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/16/15
 * Time: 1:18 PM
 */

namespace Giftcards\ModRewrite\Formatter;

use Giftcards\ModRewrite\MatchState;

class ApacheServerVariableFormatter implements FormatterInterface
{
    protected $variables;

    public function __construct()
    {
        $this->variables = array(
            'QUERY_STRING' => function (MatchState $matchState) {
                return http_build_query($matchState->getRequest()->query->all(), '', '&');
            },
            'REQUEST_URI' => function (MatchState $matchState) {
                return $matchState->getPathInfo();
            },
            'REQUEST_FILENAME' => function (MatchState $matchState) {
                return $matchState->getPathInfo();
            },
            'HTTP_HOST' => function (MatchState $matchState) {
                return $matchState->getRequest()->getHttpHost();
            },
        );
    }

    public function format(
        $value,
        MatchState $matchState
    ) {
        $variables = $this->variables;
        
        return preg_replace_callback(
            '/%{([^}]+)}/',
            function (array $matches) use ($variables, $matchState) {
                if (!isset($variables[$matches[1]])) {
                    return $matches[0];
                }
    
                return $variables[$matches[1]]($matchState);
            },
            $value
        );
    }
}
