<?php

namespace Moo\Template\Parser;

/**
 * Class SectionBlock
 */
class SectionBlock
{
    /**
     * Collection of section arguments
     *
     * @var array
     */
    static protected $arguments = [];

    /**
     * Provide a section syntax in template. Its similar implementation to <% include %>
     * but with extra features:
     *
     * - Load template by hard-coding the template name as the first parameter or provide a variable.
     * <% section 'TemplateName' %><% end_section %>
     * Or,
     * <% section $TemplateName %><% end_section %>
     *
     * - The body of the block can include HTML to be passed to the template in '$Content' variable
     * <% section 'TemplateName' %>
     *     <h1>Hello world</h1>
     * <% end_section %>
     *
     * TemplateName.ss
     * <div>
     *    {$Content} <--- will output <h1>Hello world</h1>
     * </div>
     *
     * - The body of the block can include other syntax such as <% include %>
     * <% section 'TemplateName' %>
     *     <h1>Hello world</h1>
     *     <% include Icon %>
     * <% end_section %>
     *
     * - You can pass parameters, one per line! not in same line as '<% include %>
     * <% section 'TemplateName' %>
     *     <% arg 'Theme=$Theme' %>
     *     <% arg 'Someone=Github user' %>
     *     <h1>Hello world</h1>
     * <% end_section %>
     *
     * TemplateName.ss
     * <div class="theme--{$Theme}">  <--- will output the value of argument
     *    {$Content}                  <--- will output <h1>Hello world</h1>
     *    <p>Are you a {$Someone}</p> <--- will output Github user
     * </div>
     *
     * @param array $res
     * @return string
     */
    public static function template($res)
    {
        // Get string of the section first parameter
        $templateVariable = trim($res['Arguments'][0]['text']);
        // Get string of the section first parameter without '$' from left side
        $template = ltrim($res['Arguments'][0]['text'], '$');

        // Construct an array for template extra arguments
        $arguments = '[';
        // First the values from '<% arg %>'
        foreach (static::$arguments as $name => $value) {
            $arguments .= "'" . $name . "' => " . $value . ",";
        }
        // Construct 'Content' argument that would hold the content from the body of '<% section %>'
        // The content is HTML text
        $arguments .= <<<PHP
'Content' => (function() use (\$scope) {
    \$val = '';
    {$res['Template']['php']}
    return \DBField::create_field(HTMLText::class, trim(\$val));
})(),
PHP;
        // Closing the arguments
        $arguments .= ']';

        // Clear the values from the static storage
        static::$arguments = [];

        // Construct PHP code for the template cache file to render the include template
        $php = <<<PHP
if ('{$templateVariable[0]}' === '$') {
    \$val .= \SSViewer::execute_template(
        \$scope->locally()->XML_val('{$template}', null, true), \$scope->getItem(), {$arguments}, \$scope
    );
} else {
    \$val .= \SSViewer::execute_template(
        '{$template}', \$scope->getItem(), {$arguments}, \$scope
    );
}
PHP;

        return $php;
    }

    /**
     * @param array $res
     * @return string
     */
    public static function setTemplateVar(array $res)
    {
        $name = $res['Arguments'][0]['text'];
        $php = <<<PHP
(function() use (\$scope) {
    \$val = '';
    {$res['Template']['php']}
    return \DBField::create_field(HTMLText::class, trim(\$val));
})()
PHP;
//        dd($php);

//        \SSTemplateParser::class;
//        // Remove quote from left/right of the argument
//        $argument = trim($res['Arguments'][0]['text'], "'");
//
//        // Split the argument string on the first '=' so that the first item is the argument name
//        // and the reset of the string is the argument value
//        $segments = explode('=', $argument, 2);
//        // Ensure argument name does not include '$' from left side
//        $name = ltrim($segments[0], '$');
//
//        // Store arguments in collection
        static::$arguments[$name] = $php;

        return '';
    }
}
