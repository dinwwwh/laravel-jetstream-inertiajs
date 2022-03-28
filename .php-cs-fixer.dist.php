<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PHPUnit84Migration:risky' => true,
    '@PHP80Migration:risky' => true,
    '@PHP81Migration' => true,

    '@PhpCsFixer' => true,
    '@PhpCsFixer:risky' => true,

    '@Symfony' => true, // To override the @PhpCsFixer rules
    '@Symfony:risky' => true, // To override the @PhpCsFixer:risky rules

    /* 50 - 50 : consider */
    'php_unit_internal_class' => false,
    'php_unit_test_class_requires_covers' => false,

    'declare_strict_types' => false,
    'phpdoc_no_alias_tag' => [
        'replacements' => [
            'property-read' => 'property',
            'property-write' => 'property',
            'link' => 'see',
            // 'type' => 'var',
            // Disable above replacement because it's will replace @typescript to @varscript
            // That not expected when use `spatie/laravel-typescript-transformer` package
        ],
    ],
    'phpdoc_to_comment' => false,
];

$finder = Finder::create()
    ->in([
        __DIR__,
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

$config = new Config();

return $config->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
;
