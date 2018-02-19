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
            'isDebug' => false,
            'rootDir' => $rootDir,
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

    /**
     * @dataProvider provideIsDebugDataProvider
    **/
    public function testProvideIsDebug(bool $isDebug)
    {
        $container = new Container([
            'isDebug' => $isDebug,
            'rootDir' => __DIR__,
            'config' => [],
        ]);
        $container->connect(new TwigProvider());
        $environment = $container->get('twig');
        $this->assertEquals($isDebug, $environment->isDebug(), 'MUST set debug value from config');
    }

    public function provideIsDebugDataProvider()
    {
        return [
            [
                'isDebug' => false,
            ],
            [
                'isDebug' => true,
            ],
        ];
    }

    public function testProvideCache()
    {
        $rootDir = __DIR__;
        $cacheClass = MockCache::class;
        $path = 'path/to/cache';
        $container = new Container([
            'isDebug' => false,
            'rootDir' => $rootDir,
            'config' => [
                'twig' => [
                    'cache' => [
                        'class' => $cacheClass,
                        'path' => $path,
                    ],
                ],
            ],
        ]);
        $container->connect(new TwigProvider());
        $environment = $container->get('twig');
        $cache = $environment->getCache();
        $this->assertInstanceOf($cacheClass, $cache, 'MUST return instance of provided cache class');
        $this->assertEquals("$rootDir/$path", $cache->getPath(), 'MUST prepend root dir before cache path');
    }

    public function testProvideExtensions()
    {
        $extensionClass = MockExtension::class;
        $container = new Container([
            'isDebug' => false,
            'rootDir' => __DIR__,
            'config' => [
                'twig' => [
                    'extensions' => [
                        $extensionClass,
                    ],
                ],
            ],
        ]);
        $container->connect(new TwigProvider());
        $environment = $container->get('twig');
        $contains = false;
        foreach ($environment->getExtensions() as $extension) {
            if ($extension instanceof $extensionClass) {
                $contains = true;
                break;
            }
        }
        $this->assertTrue($contains, 'MUST contain extension provided from config');
        $this->assertSame($container, $extension->getContainer(), 'MUST inject dependencies into extensions');
    }
}
