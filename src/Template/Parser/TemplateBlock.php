<?php

namespace Moo\Template\Parser;

/**
 * Class TemplateBlock.
 */
class TemplateBlock
{
    /**
     * Collection of section arguments.
     */
    protected static array $arguments = [];

    /**
     * Unique name for content argument.
     */
    protected static string $contentName = '{{___CONTENT___}}';

    /**
     * Provide a template tag in template. Its similar implementation to <% include %>.
     */
    public static function template(array $res): string
    {
        // Create unique name for content
        static::$contentName = uniqid('__CONTENT__', true);

        // Name of content argument
        $content = static::$contentName;

        // Construct an array for template extra arguments
        $arguments = '[';
        // First the values from '<% arg %>'
        foreach (static::$arguments as $name => $value) {
            $arguments .= "'".$name."' => ".$value.',';
        }

        // Construct 'Content' argument that would hold the content from the body of '<% template %>'
        // The content is HTML text.
        // Name of the argument made unique to avoid possible clash with template variables
        $arguments .= <<<PHP
'{$content}' => (function() use (\$scope) {
    \$val = '';
    {$res['Template']['php']}
    return \SilverStripe\ORM\FieldType\DBField::create_field(\SilverStripe\ORM\FieldType\DBHTMLText::class, trim(\$val));
})(),
PHP;

        // Closing the arguments
        $arguments .= ']';

        // Clear the values from the static storage
        static::$arguments = [];

        // Render template by lookup code, else argument is string, then render template by name
        if ($res['Arguments'][0]['ArgumentMode'] === 'lookup') {
            $template = str_replace('$$FINAL', 'XML_val', $res['Arguments'][0]['php']);
            $php      = <<<PHP
\$val .= \SilverStripe\View\SSViewer::execute_template(
    {$template}, \$scope->getItem(), {$arguments}, \$scope
);
PHP;
        } else {
            $template = trim($res['Arguments'][0]['text'], "'");
            $php      = <<<PHP
\$val .= \SilverStripe\View\SSViewer::execute_template(
    '{$template}', \$scope->getItem(), {$arguments}, \$scope
);
PHP;
        }

        return $php;
    }

    /**
     * Set an argument value for the current template.
     */
    public static function set(array $res): string
    {
        // Get the name of the argument
        $name = $res['Arguments'][0]['text'];

        // Generate a function to be executed to return the argument value
        $php = <<<PHP
(function() use (\$scope) {
    \$val = '';
    {$res['Template']['php']}
    return \SilverStripe\ORM\FieldType\DBField::create_field(\SilverStripe\ORM\FieldType\DBHTMLText::class, trim(\$val));
})()
PHP;

        // Store argument in static property
        static::$arguments[$name] = $php;

        // Syntax does not return any value
        return '';
    }

    /**
     * Replaces content tag with placeholder code for rendering the template content.
     */
    public static function content(array $res)
    {
        // Name of content argument
        $content = static::$contentName;

        // Generate code to extract content of the template from a internal variable
        $php = <<<PHP
\$val .= \$scope->locally()->XML_val('$content', null, true);
PHP;

        return $php;
    }
}
