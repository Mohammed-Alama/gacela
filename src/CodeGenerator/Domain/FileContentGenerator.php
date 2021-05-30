<?php

declare(strict_types=1);

namespace Gacela\CodeGenerator\Domain;

use Gacela\CodeGenerator\Domain\ReadModel\CommandArguments;
use Gacela\CodeGenerator\Infrastructure\Template\CodeTemplateInterface;

final class FileContentGenerator
{
    private CodeTemplateInterface $codeTemplate;

    public function __construct(CodeTemplateInterface $codeTemplate)
    {
        $this->codeTemplate = $codeTemplate;
    }

    public function generate(
        CommandArguments $commandArguments,
        string $moduleName
    ): void {
        $this->mkdir($commandArguments->directory());

        $path = sprintf('%s/%s.php', $commandArguments->directory(), $moduleName);
        $search = ['$NAMESPACE$', '$CLASS_NAME$'];
        $replace = [$commandArguments->namespace(), $moduleName];

        $template = $this->findTemplate($moduleName);
        $fileContent = str_replace($search, $replace, $template);

        file_put_contents($path, $fileContent);
    }

    private function mkdir(string $directory): void
    {
        if (is_dir($directory)) {
            return;
        }
        if (!mkdir($directory) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }
    }

    private function findTemplate(string $moduleName): string
    {
        switch (strtolower($moduleName)) {
            case 'facade':
                return $this->codeTemplate->getFacadeMakerTemplate();
            case 'factory':
                return $this->codeTemplate->getFactoryMakerTemplate();
            case 'config':
                return $this->codeTemplate->getConfigMakerTemplate();
            case 'dependencyprovider':
                return $this->codeTemplate->getDependencyProviderMakerTemplate();

        }
        throw new \RuntimeException('Unknown template for module ' . $moduleName);
    }
}
