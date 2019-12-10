<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/12/15
 * Time: 10:18 PM
 */

namespace Giftcards\ModRewrite\Compiler;


class Parser
{
    /**
     * @param Directive[] $directives
     * @return Configuration
     */
    public function parse(array $directives)
    {
        $engine = new Configuration();
        $conditions = [];

        foreach ($directives as $directive) {

            if ($directive->getType() == Directive::TYPE_RULE) {

                $engine->addRule(new Rule($directive, $conditions));
                $conditions = [];
            }

            if ($directive->getType() == Directive::TYPE_CONDITION) {

                $conditions[] = $directive;
            }
            
            if ($directive->getType() == Directive::TYPE_ENGINE) {
                
                $engine->setEngineOn(strtolower($directive->getPredicate()) == 'on');
            }
            
            if ($directive->getType() == Directive::TYPE_OPTIONS) {
                
                $engine->addOption($directive);
            }
            
            if ($directive->getType() == Directive::TYPE_MAP) {
                
                $engine->addMap($directive);
            }
        }
        
        return $engine;
    }
}