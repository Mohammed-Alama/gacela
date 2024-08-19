<?php

declare(strict_types=1);

namespace GacelaTest\Benchmark\FileCache\ModuleE;

use Gacela\Framework\AbstractFactory;
use Gacela\Framework\DocBlockResolverAwareTrait;
use GacelaTest\Benchmark\FileCache\ModuleE\Infra\EntityManager;
use GacelaTest\Benchmark\FileCache\ModuleE\Infra\Repository;
use GacelaTest\Fixtures\StringValueInterface;

/**
 * @method ConfigE getConfig()
 * @method Repository getRepository()
 * @method EntityManager getEntityManager()
 */
final class FactoryE extends AbstractFactory
{
    use DocBlockResolverAwareTrait;

    public function __construct(
        private StringValueInterface $stringValue,
    ) {
    }

    public function getArrayConfigAndProvidedDependency(): array
    {
        return [
            'config-key' => $this->getConfig()->getConfigValue(),
            'string-value' => $this->stringValue->value(),
            'provided-dependency' => $this->getProvidedDependency('provided-dependency'),
            'repository' => $this->getRepository()->getAll(),
            'entity-manager' => $this->getEntityManager()->updateEntity(),
        ];
    }
}