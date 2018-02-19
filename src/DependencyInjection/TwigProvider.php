<?php
namespace SD\Twig\DependencyInjection;

use SD\Config\ConfigAwareTrait;
use SD\Debug\IsDebugAwareTrait;
use SD\DependencyInjection\AutoDeclarerInterface;
use SD\DependencyInjection\AutoDeclarerTrait;
use SD\DependencyInjection\ProviderInterface;
use SD\Twig\Loader\FilesystemWithExtension;
use Twig_Environment;

class TwigProvider implements AutoDeclarerInterface, ProviderInterface
{
    use AutoDeclarerTrait;
    use ConfigAwareTrait;
    use IsDebugAwareTrait;

    public function getServiceName(): string
    {
        return 'twig';
    }

    public function provide()
    {
        $config = $this->getConfig('twig');
        $loaderConfig = $config['loader'];
        $loaderClass = $loaderConfig['class'] ?? FilesystemWithExtension::class;
        $loaderArgs = array_values($loaderConfig['args'] ?? []);
        $loader = new $loaderClass(...$loaderArgs);
        $paths = $loaderConfig['paths'] ?? [];
        foreach ($paths as $namespace => $namespacePaths) {
            foreach ($namespacePaths as $path) {
                $loader->addPath($path, $namespace);
            }
        }
        return new Twig_Environment(
            $loader,
            [
                'debug' => $this->getIsDebug(),
            ]
        );
    }
}
