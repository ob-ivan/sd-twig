<?php
namespace tests\DependencyInjection;

use SD\Twig\Extension\ExtensionFactoryInterface;

class MockExtensionFactory implements ExtensionFactoryInterface
{
    public function getExtensions(): array
    {
        return [
            new MockExtension(),
        ];
    }
}
