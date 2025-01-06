<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__ . "/src", __DIR__ . "/tests"])
    ->withPhpSets(php82: true)
    ->withPreparedSets(
        codeQuality: true,
        codingStyle: true,
        carbon: true,
        deadCode: true,
        earlyReturn: true,
        instanceOf: true,
        naming: true,
        privatization: true,
        rectorPreset: true,
        strictBooleans: true,
        typeDeclarations: true,
    )
;
