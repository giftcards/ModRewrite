<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 4:28 PM
 */

namespace Giftcards\ModRewrite\Tests\Compiler;


use Giftcards\ModRewrite\Compiler\Directive;

use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;

class DirectiveTest extends AbstractExtendableTestCase
{
    public function testGetters()
    {
        $content = 'adasdas';
        $type = Directive::TYPE_ENGINE;
        $subject = 'subj';
        $predicate = 'pred';
        $flags = ['key' => 'value', 'key2' => 'value2'];
        $directive = new Directive($content, $type, $subject, $predicate, $flags);
        $this->assertEquals($content, $directive->getContent());
        $this->assertEquals($type, $directive->getType());
        $this->assertEquals($subject, $directive->getSubject());
        $this->assertEquals($predicate, $directive->getPredicate());
        $this->assertEquals($flags, $directive->getFlags());
    }

    public function testCreateFromMatches()
    {
        $content = 'adasdas';
        $type = Directive::TYPE_ENGINE;
        $subject = 'subj';
        $predicate = 'pred';
        $flags = ['key' => 'value', 'key2' => true];
        $directive = Directive::createFromMatches([
            $content,
            $type,
            $subject,
            $predicate,
            'key=value,key2'
        ]);
        $this->assertEquals($content, $directive->getContent());
        $this->assertEquals($type, $directive->getType());
        $this->assertEquals($subject, $directive->getSubject());
        $this->assertEquals($predicate, $directive->getPredicate());
        $this->assertEquals($flags, $directive->getFlags());
    }
}
