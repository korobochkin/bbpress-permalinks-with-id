<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = new Finder()
    ->in(__DIR__)
;

return new Config()
    ->setRules([
        '@PhpCsFixer' => true,
    ])
    ->setFinder($finder)
;
