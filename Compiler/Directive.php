<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/12/15
 * Time: 10:18 PM
 */

namespace Giftcards\ModRewrite\Compiler;


class Directive
{
    const TYPE_RULE = 'RewriteRule';
    const TYPE_CONDITION = 'RewriteCond';
    const TYPE_ENGINE = 'RewriteEngine';
    const TYPE_OPTIONS = 'RewriteOptions';
    const TYPE_MAP = 'RewriteMap';

    protected $content;
    protected $type;
    protected $subject;
    protected $predicate;
    protected $flags = array();

    public static function createFromMatches(array $matches)
    {
        $flagGroups = isset($matches[4]) ? explode(',', $matches[4]) : array();
        $flags = array();

        foreach ($flagGroups as $flag) {

            $parts = explode('=', $flag);

            if (count($parts) < 2) {

                $parts[] = true;
            }

            $flags[$parts[0]] = $parts[1];
        }
        
        return new static(trim($matches[0]), $matches[1], $matches[2], $matches[3], $flags);
    }

    public function __construct($content, $type, $subject, $predicate, array $flags)
    {
        $this->content = $content;
        $this->type = $type;
        $this->subject = $subject;
        $this->predicate = $predicate;
        $this->flags = $flags;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getPredicate()
    {
        return $this->predicate;
    }

    /**
     * @return null
     */
    public function getFlags()
    {
        return $this->flags;
    }
}