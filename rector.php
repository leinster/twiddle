<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

use Rector\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__ . "/src", __DIR__ . "/tests"]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::NAMING,
        SetList::PRIVATIZATION,
        SetList::PSR_4,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
    ]);

    $rectorConfig->skip([
        NormalizeNamespaceByPSR4ComposerAutoloadRector::class => [
            __DIR__ . "/src/functions.php",
        ],
    ]);
};
