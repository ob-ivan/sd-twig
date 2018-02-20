<?php
namespace tests\Extension;

use PHPUnit\Framework\TestCase;
use SD\DependencyInjection\Container;
use SD\Twig\DependencyInjection\TwigProfileProvider;
use SD\Twig\Extension\ProfilerExtensionFactory;
use Twig_Extension_Profiler;

class ProfilerExtensionFactoryTest extends TestCase
{
    /**
     * @dataProvider getExtensionsDataProvider
    **/
    public function testGetExtensions($isDebug)
    {
        $container = new Container([
            'isDebug' => $isDebug,
        ]);
        $container->connect(new TwigProfileProvider());
        $factory = $container->produce(ProfilerExtensionFactory::class);
        $extensions = $factory->getExtensions();
        $found = false;
        foreach ($extensions as $extension) {
            if ($extension instanceof Twig_Extension_Profiler) {
                $found = true;
                break;
            }
        }
        $this->assertEquals(
            $isDebug,
            $found,
            'MUST return an instance of profiler extension only if in development environment'
        );
    }

    public function getExtensionsDataProvider()
    {
        return [
            [
                'isDebug' => true,
            ],
            [
                'isDebug' => false,
            ],
        ];
    }
}
