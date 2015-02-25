<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/12/15
 * Time: 10:19 PM
 */

namespace Giftcards\ModRewrite\Compiler;


class Rule
{
    /** @var Directive  */
    protected $rewrite;
    /** @var Directive[] */
    protected $conditions = array();

    public function __construct(Directive $rewrite, array $conditions)
    {
        $this->rewrite = $rewrite;
        $this->conditions = $conditions;
    }

    /**
     * @return Directive
     */
    public function getRewrite()
    {
        return $this->rewrite;
    }

    /**
     * @return Directive[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }
}