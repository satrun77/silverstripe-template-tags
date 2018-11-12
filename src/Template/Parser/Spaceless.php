<?php

namespace Moo\Template\Parser;

/**
 * Class Spaceless provides template tag to remove white spaces from around wrapped code.
 */
class Spaceless
{
    /**
     * Remove white spaces from around template code.
     *
     * @param $res
     * @return null|string|string[]
     */
    public static function spaceless($res)
    {
        // Pattern to find whitespaces
        $pattern = [
            '/\'(\s+)([^\s\'])/m',
            '/\'(\s+)\'/m',
            '/([^\s\'])(\s+)\';/m',
        ];

        // Template string code to remove whitespaces from
        $php = $res['Template']['php'];

        // Remove whitespaces
        $php = preg_replace($pattern, ["'$2", "''", "$1';"], $php);

        return $php;
    }
}
