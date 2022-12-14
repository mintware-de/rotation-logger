<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@PSR12' => true,
        'phpdoc_align' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);

