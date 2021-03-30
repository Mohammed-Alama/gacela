<?php

declare(strict_types=1);

namespace GacelaTest\Integration\ModuleWithExternalDependencies\Supplier;

use Gacela\AbstractFactory;
use GacelaTest\Integration\ModuleWithExternalDependencies\Dependent;
use GacelaTest\Integration\ModuleWithExternalDependencies\Supplier\Service\HelloName;

final class Factory extends AbstractFactory
{
    public function createGreeter(): HelloName
    {
        return new HelloName(
            $this->getDependentFacade()
        );
    }

    private function getDependentFacade(): Dependent\FacadeInterface
    {
        return $this->getProvidedDependency(DependencyProvider::FACADE_DEPENDENT);
    }
}