<?php

namespace Moo\Test;

use SilverStripe\Dev\SapphireTest;
//use PHPUnit_Framework_TestCase;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;
use SilverStripe\View\TemplateGlobalProvider;

/**
 * @internal
 * @coversNothing
 */
class ParseSpacelessTest extends SapphireTest implements TemplateGlobalProvider
{
    public function testSimpleSpaceCleanUp()
    {
        $template = <<<'Template'
<% spaceless %>
    ... Template syntax  and HTML ...
<% end_spaceless %>
Template;

        $output = SSViewer::execute_string($template, []);

        $this->assertEquals('... Template syntax and HTML ...', $output);
        $this->assertNotEquals('... Template syntax and HTML ... ', $output);
        $this->assertNotEquals(' ... Template syntax and HTML ...', $output);
    }

    public function testWithTemplateFunctionIncludeValidSpaces()
    {
        $template = <<<'Template'
<% spaceless %>
    ... Template syntax and <strong>One</strong> <strong> Two</strong>HTML$Concat(' ', 'One', ' ', 'Two', ' - ', 'Three') ...
    ... Template syntax and HTML{$Concat(' ', 'One', ' ', 'Two', ' - ', 'Three')}...
    ...
<% end_spaceless %>
Template;
        $output = SSViewer::execute_string($template, []);
        $this->assertEquals(
            '... Template syntax and <strong>One</strong><strong> Two</strong>HTML One Two - Three ... ... Template syntax and HTML One Two - Three... ...',
            $output
        );
    }

    public function testWithTemplateFunctionIncludeHtml()
    {
        $template = <<<'Template'
<% spaceless %>
    {$Hello($Name).Spaceless}!
    
    <div>Some random text....</div>
<% end_spaceless %>
Template;

        $data   = ArrayData::create(['Name' => 'First  Name']);
        $output = SSViewer::execute_string($template, $data);
        $this->assertEquals(
            '<div><span>Hello</span><strong>First Name</strong></div>! <div>Some random text....</div>',
            $output
        );
    }

    public static function get_template_global_variables()
    {
        return [
            'Hello' => [
                'method'  => 'Hello',
                'casting' => 'HTMLText',
            ],
            'Concat' => [
                'method'  => 'Concat',
                'casting' => 'HTMLText',
            ],
        ];
    }

    public static function Hello(?string $name): string
    {
        return html_entity_decode(
            '<div> <span>Hello</span> <strong>'.$name.'</strong></div>',
            ENT_QUOTES | ENT_XML1,
            'UTF-8'
        );
    }

    public static function Concat(): string
    {
        return html_entity_decode(implode('', func_get_args()), ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    public static function tearDownAfterClass()
    {
        // Disable teardown to prevent db access
    }
}
