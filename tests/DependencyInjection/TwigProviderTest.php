<?php
namespace tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use SD\Twig\DependencyInjection\TwigProvider;

class TwigProviderTest extends TestCase
{
    public function testProvideLoader()
    {
        $loaderClass = MockLoader::class;
        $relativePath = 'path/to/templates';
        $rootDir = __DIR__;
        $extra = mt_rand();
        $namespace = 'someNamespace';
        $namespaceRelativePaths = ['a', 'b', 'c'];
        $container = new Container([
            'config' => [
                'twig' => [
                    'loader' => [
                        'class' => $loaderClass,
                        'args' => [
                            'path' => $relativePath,
                            'rootDir' => $rootDir,
                            'extra' => $extra,
                        ],
                        'paths' => [
                            $namespace => $namespacePaths,
                        ],
                    ],
                ],
            ],
        ]);
        $container->connect(new TwigProvider());
        $environment = $container->get('twig');
        $this->assertInstanceOf(Twig_Environment::class, $environment, 'MUST return instance of twig environment');
        $loader = $environment->getLoader();
        $this->assertInstanceOf($loaderClass, $loader, 'MUST return instance of provided loader class');
        $this->assertEquals(
            [$rootDir . '/' . $relativePath],
            $loader->getPaths(),
            'MUST set path for main namespace from constructor AND prepend root directory'
        );
        $this->assertEquals(
            $extra,
            $loader->getExtra(),
            'MUST pass extra parameters from config'
        );
        $this->assertEquals(
            array_map(
                function ($relativePath) use ($rootDir) {
                    return $rootDir . '/' . $relativePath;
                },
                $namespaceRelativePaths
            ),
            $loader->getPaths($namespace),
            'MUST set paths for custom namespace from config AND prepend root directory'
        );
    }
}
