<?php
namespace SD\Twig\DependencyInjection;

use SD\Config\ConfigAwareTrait;
use SD\DependencyInjection\AutoDeclarerInterface;
use SD\DependencyInjection\AutoDeclarerTrait;
use SD\DependencyInjection\ProviderInterface;
use Twig_Environment;

class TwigProvider implements AutoDeclarerInterface, ProviderInterface
{
    use AutoDeclarerTrait;
    use ConfigAwareTrait;

    public function getServiceName(): string
    {
        return 'twig';
    }

    public function provide()
    {
        $config = $this->getConfig('twig');
        $loaderConfig = $config['loader'];
        $loaderClass = $loaderConfig['class'];
        $loaderArgs = array_values($loaderConfig['args']);
        $loader = new $loaderClass(...$loaderArgs);
        return new Twig_Environment($loader);
    }
}
