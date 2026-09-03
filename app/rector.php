<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;

return RectorConfig::configure()
    ->withParallel()
    ->withCache(
        cacheDirectory: __DIR__ . '/var/rector',
        cacheClass: FileCacheStorage::class,
    )
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/.php-cs-fixer.dist.php',
        __DIR__ . '/rector.php',
    ])
    ->withImportNames(importShortClasses: false)
    ->withSets([
        PHPUnitSetList::COMPOSER_BASED,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
    ])
    ->withPhpSets(php85: true)
    ->withPreparedSets(
        typeDeclarations: true,
    );
