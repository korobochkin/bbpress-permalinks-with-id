<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = new Finder()
    ->in(__DIR__)
;

return new Config()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PhpCsFixer' => true,
        'fully_qualified_strict_types' => [
            'import_symbols' => true,
            'phpdoc_tags' => [],
        ],
    ])
    ->setFinder($finder)
;
