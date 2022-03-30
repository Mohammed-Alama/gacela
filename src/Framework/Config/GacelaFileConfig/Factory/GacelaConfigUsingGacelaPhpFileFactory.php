<?php

declare(strict_types=1);

namespace Gacela\Framework\Config\GacelaFileConfig\Factory;

use Gacela\Framework\Config\FileIoInterface;
use Gacela\Framework\Config\GacelaConfigBuilder\ConfigBuilder;
use Gacela\Framework\Config\GacelaConfigBuilder\MappingInterfacesBuilder;
use Gacela\Framework\Config\GacelaConfigBuilder\SuffixTypesBuilder;
use Gacela\Framework\Config\GacelaConfigFileFactoryInterface;
use Gacela\Framework\Config\GacelaFileConfig\GacelaConfigFile;
use Gacela\Framework\Config\GacelaFileConfig\GacelaConfigFileInterface;
use Gacela\Framework\Setup\SetupGacelaInterface;
use RuntimeException;
use function is_callable;

final class GacelaConfigUsingGacelaPhpFileFactory implements GacelaConfigFileFactoryInterface
{
    private string $gacelaPhpPath;

    private SetupGacelaInterface $setup;

    private FileIoInterface $fileIo;

    public function __construct(
        string $gacelaPhpPath,
        SetupGacelaInterface $setup,
        FileIoInterface $fileIo
    ) {
        $this->gacelaPhpPath = $gacelaPhpPath;
        $this->setup = $setup;
        $this->fileIo = $fileIo;
    }

    public function createGacelaFileConfig(): GacelaConfigFileInterface
    {
        $setupGacelaFn = $this->fileIo->include($this->gacelaPhpPath);
        if (!is_callable($setupGacelaFn)) {
            throw new RuntimeException('Create a function that returns an anonymous class that implements SetupGacelaInterface');
        }

        /** @var object $setupGacela */
        $setupGacela = $setupGacelaFn();
        if (!is_subclass_of($setupGacela, SetupGacelaInterface::class)) {
            throw new RuntimeException('Your anonymous class must implements SetupGacelaInterface');
        }

        $configBuilder = $this->createConfigBuilder($setupGacela);
        $mappingInterfacesBuilder = $this->createMappingInterfacesBuilder($setupGacela);
        $suffixTypesBuilder = $this->createSuffixTypesBuilder($setupGacela);

        return (new GacelaConfigFile())
            ->setConfigItems($configBuilder->build())
            ->setMappingInterfaces($mappingInterfacesBuilder->build())
            ->setSuffixTypes($suffixTypesBuilder->build());
    }

    private function createConfigBuilder(SetupGacelaInterface $setupGacela): ConfigBuilder
    {
        $configBuilder = new ConfigBuilder();
        $setupGacela->config($configBuilder);

        return $configBuilder;
    }

    private function createMappingInterfacesBuilder(SetupGacelaInterface $setupGacela): MappingInterfacesBuilder
    {
        $mappingInterfacesBuilder = new MappingInterfacesBuilder();
        $setupGacela->mappingInterfaces($mappingInterfacesBuilder, $this->setup->externalServices());

        return $mappingInterfacesBuilder;
    }

    private function createSuffixTypesBuilder(SetupGacelaInterface $setupGacela): SuffixTypesBuilder
    {
        $suffixTypesBuilder = new SuffixTypesBuilder();
        $setupGacela->suffixTypes($suffixTypesBuilder);

        return $suffixTypesBuilder;
    }
}
