<?php
namespace SD\Twig\Extension;

use SD\Debug\IsDebugAwareTrait;
use SD\DependencyInjection\AutoDeclarerInterface;
use SD\DependencyInjection\AutoDeclarerTrait;
use SD\Twig\DependencyInjection\TwigProfileAwareTrait;
use Twig_Extension_Profiler;

class ProfilerExtensionFactory implements AutoDeclarerInterface, ExtensionFactoryInterface
{
    use AutoDeclarerTrait;
    use IsDebugAwareTrait;
    use TwigProfileAwareTrait;

    public function getExtensions(): array
    {
        $extensions = [];
        if ($this->getIsDebug()) {
            $extensions[] = new Twig_Extension_Profiler($this->getTwigProfile());
        }
        return $extensions;
    }
}
