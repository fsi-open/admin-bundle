<?php

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        'declare_strict_types' => true,
        'array_syntax' => ['syntax' => 'short'],
        'header_comment' => ['header' => ""],
        'blank_line_after_opening_tag' => true,
        'single_blank_line_before_namespace' => true,
        'cast_spaces' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/src')
            ->in(__DIR__ . '/tests')
            ->in(__DIR__ . '/spec')
    )->setRiskyAllowed(true)
    ->setUsingCache(false);