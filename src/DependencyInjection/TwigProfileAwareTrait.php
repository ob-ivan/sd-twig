<?php
namespace SD\Twig\DependencyInjection;

use Twig_Profiler_Profile;

trait TwigProfileAwareTrait
{
    protected $autoDeclareTwigProfile = 'twigProfile';
    private $twigProfile;

    public function setTwigProfile(Twig_Profiler_Profile $twigProfile)
    {
        $this->twigProfile = $twigProfile;
    }

    public function getTwigProfile(): Twig_Profiler_Profile
    {
        return $this->twigProfile;
    }
}
