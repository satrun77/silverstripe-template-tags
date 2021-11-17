<?php

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR1' => true,
    '@PSR2' => true,
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    '@Symfony' => true,
    'php_unit_test_class_requires_covers' => false,
    'protected_to_private' => false,
    'yoda_style' => false,
    'no_trailing_comma_in_list_call' => false,
    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => null,
        'import_functions' => null,
    ],
    'concat_space' => [
        'spacing' => 'one',
    ],
    'binary_operator_spaces' => [
        'default' => 'align',
    ],
    'list_syntax' => [
        'syntax' => 'short',
    ],
    'array_syntax' => ['syntax' => 'short'],
    'self_static_accessor' => true,
    'mb_str_functions' => true,
])->setUsingCache(false);
