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
        $namespaceArray = 'namespaceArray';
        $namespaceArrayPaths = ['templates'];
        $namespaceString = 'namespaceString';
        $namespaceStringPaths = 'templates';
        $container = new Container([
            'isDebug' => false,
            'rootDir' => $rootDir,
            'config' => [
                'twig' => [
                    'loader' => [
                        'class' => $loaderClass,
                        'path' => $relativePath,
                        'rootDir' => '',
                        'extra' => [
                            $extra,
                        ],
                        'paths' => [
                            $namespaceArray => $namespaceArrayPaths,
                            $namespaceString => $namespaceStringPaths,
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
            $namespaceArrayPaths,
            $loader->getPaths($namespaceArray),
            'MUST set paths for custom namespace from array config'
        );
        $this->assertEquals(
            [$namespaceStringPaths],
            $loader->getPaths($namespaceString),
            'MUST set paths for custom namespace from string config'
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
        $this->assertSame($container, $extension->getContainerPublic(), 'MUST inject dependencies into extensions');
    }

    public function testProvideExtensionFactories()
    {
        $container = new Container([
            'isDebug' => false,
            'rootDir' => __DIR__,
            'config' => [
                'twig' => [
                    'extension_factories' => [
                        MockExtensionFactory::class,
                    ],
                ],
            ],
        ]);
        $container->connect(new TwigProvider());
        $environment = $container->get('twig');
        $contains = false;
        foreach ($environment->getExtensions() as $extension) {
            if ($extension instanceof MockExtension) {
                $contains = true;
                break;
            }
        }
        $this->assertTrue($contains, 'MUST contain extension provided from factory');
    }
}
