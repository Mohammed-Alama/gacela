<?php

declare(strict_types=1);

namespace Gacela\Framework\Config\ConfigReader;

use Gacela\Framework\Config\ConfigReaderInterface;

final class PhpConfigReader implements ConfigReaderInterface
{
    public function canRead(string $absolutePath): bool
    {
        $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);

        return 'php' === $extension;
    }

    /**
     * @return array<array-key, string>
     */
    public function read(string $absolutePath): array
    {
        if (!file_exists($absolutePath)) {
            return [];
        }

        /** @var null|string[] $content */
        $content = include $absolutePath;

        return is_array($content) ? $content : [];
    }
}
