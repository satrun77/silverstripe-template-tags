<?php

namespace Moo\Test;

use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;

class ParseTemplateTest extends SapphireTest
{
    public function testTemplateUsage()
    {
        // Template to render
        $template = <<<'Template'
<% template Card %>
    <% set Name %>Full Name<% end_set %>
    <% set Website %>
        View portfolio: <a href="{$Link.URL}">{$Link.Title}</a>
    <% end_set %>

    <h1>Hello world</h1>
    <% include Tags %>
<% end_template %>
Template;

        // The expected output
        $expected = <<<'Template'
<div class="card">
    <h1>Hello world</h1>
    <div>Tag1, Tag2</div>
    <div>
        <div>Full Name</div>
        <div>View portfolio: <a href="http://portfolio.com">portfolio.com</a></div>
    </div>
</div>
Template;

        // Assert template output matches the expected
        $this->assertTemplate($template, $expected);
    }

    public function testTemplateNameWithNamespace()
    {
        // Template to render
        $template = <<<'Template'
<% template 'Namespace\Card' %>
    <% set Name %>Full Name<% end_set %>
    <% set Website %>
        View portfolio: <a href="{$Link.URL}">{$Link.Title}</a>
    <% end_set %>

    <h1>Hello world</h1>

<% end_template %>
Template;

        // The expected output
        $expected = <<<'Template'
<div class="card card--namespace">
    <h1>Hello world</h1>
    <div>
        <div>Full Name</div>
        <div>View portfolio: <a href="http://portfolio.com">portfolio.com</a></div>
    </div>
</div>
Template;

        // Assert template output matches the expected
        $this->assertTemplate($template, $expected);
    }

    public function testTemplateWithVarAsFileName()
    {
        // Template to render
        $template = <<<'Template'
<% template $MyTemplate %>
    <% set Name %>Full Name<% end_set %>
    <% set Website %>
        View portfolio: <a href="{$Link.URL}">{$Link.Title}</a>
    <% end_set %>

    <h1>Hello world</h1>

<% end_template %>
Template;

        // The expected output
        $expected = <<<'Template'
<div class="card card--var">
    <h1>Hello world</h1>
    <div>
        <div>Full Name</div>
        <div>View portfolio: <a href="http://portfolio.com">portfolio.com</a></div>
    </div>
</div>
Template;

        // Assert template output matches the expected
        $this->assertTemplate($template, $expected);
    }

    public function testTemplateNameFromMethod()
    {
        // Template to render
        $template = <<<'Template'
<% template $Me.CoolTemplate %>
    <% set Name %>Full Name<% end_set %>
    <% set Website %>
        View portfolio: <a href="{$Link.URL}">{$Link.Title}</a>
    <% end_set %>

    <h1>Hello world</h1>

<% end_template %>
Template;

        // The expected output
        $expected = <<<'Template'
<div class="card card--method">
    <h1>Hello world</h1>
    <div>
        <div>Full Name</div>
        <div>View portfolio: <a href="http://portfolio.com">portfolio.com</a></div>
    </div>
</div>
Template;

        // Assert template output matches the expected
        $this->assertTemplate($template, $expected);
    }

    /**
     * Get data to be used for template testing.
     */
    protected function getTemplateData(): ArrayData
    {
        return new class() extends ArrayData {
            public function __construct()
            {
                parent::__construct([
                    'MyTemplate' => 'Namespace\CardVar',
                    'Link'       => [
                        'URL'   => 'http://portfolio.com',
                        'Title' => 'portfolio.com',
                    ],
                ]);
            }

            public function getCoolTemplate(): string
            {
                return 'Namespace\CardMethod';
            }
        };
    }

    /**
     * Compile and assert template.
     */
    protected function assertTemplate(string $template, string $expected): void
    {
        // Data to use as template Top context
        $data = $this->getTemplateData();

        // Add path to templates used for testing
        Config::modify()->set(SSViewer::class, 'themes', [
            '$public',
            __DIR__.'/support',
            '$default',
        ]);

        // Render template
        $output = SSViewer::execute_string($template, $data);

        // Assert template output matches the expected
        $this->assertXmlStringEqualsXmlString($expected, $output);
    }

    public static function tearDownAfterClass()
    {
        // Disable teardown to prevent db access
    }
}
