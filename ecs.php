<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\Operator\StandardizeIncrementFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitInternalClassFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestClassRequiresCoversFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withRootFiles()
    ->withSkip([
        SingleQuoteFixer::class,
        StandardizeIncrementFixer::class,
        PhpUnitInternalClassFixer::class,
        PhpUnitTestClassRequiresCoversFixer::class,
        FunctionDeclarationFixer::class,
    ])
    ->withConfiguredRule(LineLengthFixer::class, [
        "line_length" => 80,
    ],)
    ->withPreparedSets(
        common: true,
        strict: true,
        cleanCode: true,
        psr12: true,
        symplify: true,
    )
    ->withPhpCsFixerSets(
        perCS: true,
        psr1: true,
        psr12: true,
        phpCsFixer: true,
    )
;
