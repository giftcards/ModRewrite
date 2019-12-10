<?php
/**
 * Created by PhpStorm.
 * User: jderay
 * Date: 2/24/15
 * Time: 3:18 PM
 */

namespace Giftcards\ModRewrite\Tests\Compiler;


use Giftcards\ModRewrite\Compiler\Directive;
use Giftcards\ModRewrite\Compiler\Lexer;

use Omni\TestingBundle\TestCase\Extension\AbstractExtendableTestCase;

class LexerTest extends AbstractExtendableTestCase
{
    /** @var  Lexer */
    protected $lexer;

    public function setUp() :void
    {
        $this->lexer = new Lexer();
    }

    public function testLex()
    {
        $content = <<<CONTENT
RewriteEngine On
RewriteRule ^/?cards-images/(.*)$ /image_scripts/card.php?image=$1 [L]

RewriteCond %{REQUEST_URI} ^/gifts-for-husband     [NC,OR]
RewriteCond %{REQUEST_URI} ^/gifts-for-son         [NC]
RewriteRule ^(.*)$          /gifts-for-him         [R=301,L]

RewriteLog "fddsffdsdsf"
RewriteLogLevel 5

RewriteOptions Inherit
RewriteMap examplemap txt:/path/to/file/map.txt

CONTENT;

        $this->assertEquals([
            Directive::createFromMatches(['RewriteEngine On', 'RewriteEngine', '', 'On']),
            Directive::createFromMatches(['RewriteRule ^/?cards-images/(.*)$ /image_scripts/card.php?image=$1 [L]', 'RewriteRule', '^/?cards-images/(.*)$', '/image_scripts/card.php?image=$1', 'L']),
            Directive::createFromMatches(['RewriteCond %{REQUEST_URI} ^/gifts-for-husband     [NC,OR]', 'RewriteCond', '%{REQUEST_URI}', '^/gifts-for-husband', 'NC,OR']),
            Directive::createFromMatches(['RewriteCond %{REQUEST_URI} ^/gifts-for-son         [NC]', 'RewriteCond', '%{REQUEST_URI}', '^/gifts-for-son', 'NC']),
            Directive::createFromMatches(['RewriteRule ^(.*)$          /gifts-for-him         [R=301,L]', 'RewriteRule', '^(.*)$', '/gifts-for-him', 'R=301,L']),
            Directive::createFromMatches(['RewriteLog "fddsffdsdsf"', 'RewriteLog', '', '"fddsffdsdsf"']),
            Directive::createFromMatches(['RewriteLogLevel 5', 'RewriteLogLevel', '', '5']),
            Directive::createFromMatches(['RewriteOptions Inherit', 'RewriteOptions', '', 'Inherit']),
            Directive::createFromMatches(['RewriteMap examplemap txt:/path/to/file/map.txt', 'RewriteMap', 'examplemap', 'txt:/path/to/file/map.txt']),
        ], $this->lexer->lex($content));
    }
}
