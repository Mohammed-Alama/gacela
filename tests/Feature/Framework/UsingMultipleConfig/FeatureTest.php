<?php

declare(strict_types=1);

namespace GacelaTest\Feature\Framework\UsingMultipleConfig;

use Gacela\Framework\Config\GacelaConfigBuilder\ConfigBuilder;
use Gacela\Framework\Gacela;
use GacelaTest\Fixtures\SimpleEnvConfigReader;
use PHPUnit\Framework\TestCase;

final class FeatureTest extends TestCase
{
    public function setUp(): void
    {
        $globalServices = [
            'config' => static function (ConfigBuilder $configBuilder): void {
                $configBuilder->add('config/.env*', '', SimpleEnvConfigReader::class);
                $configBuilder->add('config/*.php');
            },
        ];

        Gacela::bootstrap(__DIR__, $globalServices);
    }

    public function test_load_multiple_config_files(): void
    {
        $facade = new LocalConfig\Facade();

        self::assertSame(
            [
                'config-env' => 1,
                'config-php' => 3,
                'override' => 4,
                'local' => 5,
            ],
            $facade->doSomething()
        );
    }
}