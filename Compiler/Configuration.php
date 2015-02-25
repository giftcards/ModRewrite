<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/12/15
 * Time: 11:02 PM
 */

namespace Giftcards\ModRewrite\Compiler;


class Configuration
{
    protected $rules = array();
    protected $engineOn = false;
    protected $options = array();
    protected $maps = array();

    public function setEngineOn($engineOn)
    {
        $this->engineOn = $engineOn;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEngineOn()
    {
        return $this->engineOn;
    }

    public function addRule(Rule $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * @return Rule[]
     */
    public function getRules()
    {
        return $this->rules;
    }

    public function addOption(Directive $option)
    {
        $this->options[] = $option;
        return $this;
    }

    /**
     * @return Directive[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    public function addMap(Directive $map)
    {
        $this->maps[] = $map;
        return $this;
    }

    /**
     * @return Directive[]
     */
    public function getMaps()
    {
        return $this->maps;
    }
    
}