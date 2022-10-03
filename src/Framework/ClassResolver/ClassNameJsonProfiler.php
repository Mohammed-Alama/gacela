<?php

declare(strict_types=1);

namespace Gacela\Framework\ClassResolver;

final class ClassNameJsonProfiler extends AbstractJsonFileProfiler
{
    public const FILENAME = 'gacela-class-names.json';

    protected function getCacheFilename(): string
    {
        return self::FILENAME;
    }
}