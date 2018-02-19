<?php
namespace SD\Twig\DependencyInjection;

use Twig_Environment;

trait TwigAwareTrait
{
    protected $autoDeclareTwig = 'twig';
    private $twig;

    public function setTwig(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getTwig()
    {
        return $this->twig;
    }
}
