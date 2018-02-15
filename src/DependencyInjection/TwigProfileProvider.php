<?php
namespace SD\Twig\DependencyInjection;

use SD\DependencyInjection\ProviderInterface;
use Twig_Profiler_Profile;

class TwigProfileProvider implements ProviderInterface
{
    public function getServiceName(): string
    {
        return 'twigProfile';
    }

    public function provide()
    {
        return new Twig_Profiler_Profile();
    }
}
