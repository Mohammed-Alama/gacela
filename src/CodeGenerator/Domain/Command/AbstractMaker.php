<?php

declare(strict_types=1);

namespace Gacela\CodeGenerator\Domain\Command;

use Gacela\CodeGenerator\Domain\Io\MakerIoInterface;
use Gacela\CodeGenerator\Domain\ReadModel\CommandArguments;

abstract class AbstractMaker implements MakerInterface
{
    private MakerIoInterface $io;

    public function __construct(MakerIoInterface $io)
    {
        $this->io = $io;
    }

    public function make(CommandArguments $commandArguments): void
    {
        $pieces = explode('/', $commandArguments->targetDirectory());
        $moduleName = end($pieces);

        $this->io->createDirectory($commandArguments->targetDirectory());

        $path = sprintf('%s/%s.php', $commandArguments->targetDirectory(), $this->className());
        $this->io->filePutContents($path, $this->generateFileContent("{$commandArguments->rootNamespace()}\\$moduleName"));

        $this->io->writeln("> Path '$path' created successfully");
    }

    abstract protected function generateFileContent(string $namespace): string;

    abstract protected function className(): string;
}
