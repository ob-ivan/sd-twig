<?php
namespace tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use SD\DependencyInjection\Container;
use SD\Twig\DependencyInjection\TwigProvider;
use Twig_Environment;

class TwigProviderTest extends TestCase
{
    public function testProvideLoader()
    {
        $loaderClass = MockLoader::class;
        $relativePath = 'templates';
        $rootDir = __DIR__;
        $extra = mt_rand();
        $namespace = 'someNamespace';
        $namespaceRelativePaths = ['templates'];
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
                            $namespace => $namespaceRelativePaths,
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
            [$relativePath],
            $loader->getPaths(),
            'MUST set path for main namespace from constructor'
        );
        $this->assertEquals(
            $extra,
            $loader->getExtra(),
            'MUST pass extra parameters from config'
        );
        $this->assertEquals(
            $namespaceRelativePaths,
            $loader->getPaths($namespace),
            'MUST set paths for custom namespace from config'
        );
    }
}
