<?php
namespace SD\Twig\DependencyInjection;

use SD\Config\ConfigAwareTrait;
use SD\Config\RootDirAwareTrait;
use SD\Debug\IsDebugAwareTrait;
use SD\DependencyInjection\AutoDeclarerInterface;
use SD\DependencyInjection\AutoDeclarerTrait;
use SD\DependencyInjection\ContainerAwareTrait;
use SD\DependencyInjection\ProviderInterface;
use SD\Twig\Cache\FilesystemWithUmask;
use SD\Twig\Loader\FilesystemWithExtension;
use Twig_Environment;

class TwigProvider implements AutoDeclarerInterface, ProviderInterface
{
    use AutoDeclarerTrait;
    use ConfigAwareTrait;
    use ContainerAwareTrait;
    use IsDebugAwareTrait;
    use RootDirAwareTrait;

    public function getServiceName(): string
    {
        return 'twig';
    }

    public function provide()
    {
        $environment = new Twig_Environment(
            $this->getLoader(),
            [
                'debug' => $this->getIsDebug(),
                'cache' => $this->getCache(),
            ]
        );
        foreach ($this->getExtensions() as $extension) {
            $environment->addExtension($extension);
        }
        return $environment;
    }

    private function getLoader()
    {
        $config = $this->getConfig('twig');
        $loaderConfig = $config['loader'] ?? [];
        $loaderClass = $loaderConfig['class'] ?? FilesystemWithExtension::class;
        $loaderArgs = array_values($loaderConfig['args'] ?? []);
        $loader = new $loaderClass(...$loaderArgs);
        $paths = $loaderConfig['paths'] ?? [];
        foreach ($paths as $namespace => $namespacePaths) {
            foreach ($namespacePaths as $path) {
                $loader->addPath($path, $namespace);
            }
        }
        return $loader;
    }

    private function getCache()
    {
        $config = $this->getConfig('twig');
        $cacheConfig = $config['cache'] ?? [];
        if (!$cacheConfig) {
            return false;
        }
        $cacheClass = $cacheConfig['class'] ?? FilesystemWithUmask::class;
        $cachePath = $cacheConfig['path'] ?? '';
        return new $cacheClass("{$this->getRootDir()}/$cachePath");
    }

    private function getExtensions()
    {
        $config = $this->getConfig('twig');
        $extensionsConfig = $config['extensions'] ?? [];
        $extensions = [];
        foreach ($extensionsConfig as $extensionClass) {
            $extensions[] = $this->getContainer()->produce($extensionClass);
        }
        return $extensions;
    }
}
