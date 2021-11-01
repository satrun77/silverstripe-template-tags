<?php

namespace Moo\Template\Parser;

/**
 * Class Spaceless provides template tag to remove white spaces from around wrapped code.
 */
class Spaceless
{
    /**
     * Remove space from the start of the statement.
     */
    protected static array $patternStart = [
        '/\'(\s+)([^\s\'])/m',
        "'$2",
    ];

    /**
     * Remove space from between HTML tags.
     */
    protected static array $patternBetweenTag = [
        '/>\s+</',
        '><',
    ];

    /**
     * Remove space from the end of the statement.
     */
    protected static array $patternEnd = [
        '/([^\s\'])(\s+)\';/m',
        "$1';",
    ];

    /**
     * Convert multiple spaces into one space.
     */
    protected static array $patternOneSpace = [
        '!\s+!',
        ' ',
    ];

    /**
     * Remove space from empty statement `$val .= '  ';`.
     */
    protected static array $patternEmpty = [
        '/\'(\s+)\';/m',
        "'';",
    ];

    /**
     * Remove white spaces from around template code.
     *
     * @param array $res
     *
     * @return ?string
     */
    public static function spaceless($res)
    {
        // Template string code to remove whitespaces from
        $php = $res['Template']['php'];

        // Convert into an array
        $code = static::toStatementPerLine($php);

        // Clean up each i  tem in the array
        $index = 0;
        foreach ($code as $key => $line) {
            $code[$key] = static::cleanCode((string) $line, $index);
            ++$index;
        }

        return implode("\n", $code);
    }

    /**
     * Clean up a line of code based on its position `$index`.
     */
    protected static function cleanCode(string $value, int $index): string
    {
        // Default replacements
        $pattern = [
            static::$patternEnd[0],
            static::$patternOneSpace[0],
            static::$patternEmpty[0],
            static::$patternBetweenTag[0],
        ];

        $replacement = [
            static::$patternEnd[1],
            static::$patternOneSpace[1],
            static::$patternEmpty[1],
            static::$patternBetweenTag[1],
        ];

        // Replacements for first line of code
        if ($index === 0) {
            $pattern = [
                static::$patternStart[0],
                static::$patternEnd[0],
                static::$patternOneSpace[0],
                static::$patternEmpty[0],
                static::$patternBetweenTag[0],
            ];

            $replacement = [
                static::$patternStart[1],
                static::$patternEnd[1],
                static::$patternOneSpace[1],
                static::$patternEmpty[1],
                static::$patternBetweenTag[1],
            ];
        }

        return preg_replace($pattern, $replacement, $value);
    }

    /**
     * Convert the PHP string into an array of items. One statement per line.
     */
    protected static function toStatementPerLine(string $value): array
    {
        // Convert string into single line string
        $value = str_replace(["\r\n", "\n", "\r", "\t"], '', $value);
        // Add break-line before every start of a statement `$val .=`
        $value = str_replace('$val .=', "\n\$val .=", $value);

        // Convert the string into an array item per line
        return array_filter(explode("\n", $value));
    }
}
