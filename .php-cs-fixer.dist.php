<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect()) // @TODO 4.0 no need to call this manually
    ->setRiskyAllowed(true)
    ->setRules([
    // PSR-12 (base segura)
    '@PSR12' => true,

    // Arrays
    'array_syntax' => ['syntax' => 'short'],
    'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']],
    'whitespace_after_comma_in_array' => true,

    // Imports
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'no_unused_imports' => true,
    'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],

    // Espaçamento
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => ['statements' => ['return', 'try', 'throw']],
    'no_extra_blank_lines' => ['tokens' => ['extra', 'throw', 'use']],
    'single_blank_line_at_eof' => true,

    // Strings
    'single_quote' => true,
    'concat_space' => ['spacing' => 'one'],

    // Funções/Métodos
    'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
    'no_spaces_after_function_name' => true,
    'return_type_declaration' => ['space_before' => 'none'],

    // Classes
    'class_attributes_separation' => ['elements' => ['method' => 'one', 'property' => 'one']],
    'visibility_required' => ['elements' => ['property', 'method', 'const']],

    // Operadores
    'binary_operator_spaces' => ['default' => 'single_space'],
    'unary_operator_spaces' => true,

    // Casting
    'cast_spaces' => ['space' => 'single'],
    'lowercase_cast' => true,

    // PHP moderno
    'declare_strict_types' => true,
    'native_function_invocation' => ['include' => ['@all']],

    // Limpeza
    'no_empty_comment' => true,
    'no_empty_phpdoc' => true,
    'no_leading_namespace_whitespace' => true,
    'no_trailing_whitespace' => true,
    'no_whitespace_in_blank_line' => true,

    // Desabilita regras que quebram código
    'phpdoc_to_comment' => false,
    'comment_to_phpdoc' => false,
    'php_unit_internal_class' => false,
    'php_unit_test_class_requires_covers' => false,
    ])
    // 💡 by default, Fixer looks for `*.php` files excluding `./vendor/` - here, you can groom this config
    ->setFinder(
        (new Finder())
            // 💡 root folder to check
            ->in(__DIR__)
            // 💡 additional files, eg bin entry file
            // ->append([__DIR__.'/bin-entry-file'])
            // 💡 folders to exclude, if any
            // ->exclude([/* ... */])
            // 💡 path patterns to exclude, if any
            // ->notPath([/* ... */])
            // 💡 extra configs
            // ->ignoreDotFiles(false) // true by default in v3, false in v4 or future mode
            // ->ignoreVCS(true) // true by default
    )
;
