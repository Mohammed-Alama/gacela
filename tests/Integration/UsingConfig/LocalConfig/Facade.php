<?php

declare(strict_types=1);

namespace GacelaTest\Integration\UsingConfig\LocalConfig;

use Gacela\AbstractFacade;

/**
 * @method Factory getFactory()
 */
final class Facade extends AbstractFacade
{
    public function doSomething(): array
    {
        return $this->getFactory()->getArrayConfig();
    }
}