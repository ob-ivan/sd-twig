<?php
namespace tests\DependencyInjection;

use SD\DependencyInjection\AutoDeclarerInterface;
use SD\DependencyInjection\AutoDeclarerTrait;
use SD\DependencyInjection\ContainerAwareTrait;
use Twig_Extension;

class MockExtension extends Twig_Extension implements AutoDeclarerInterface
{
    use AutoDeclarerTrait;
    use ContainerAwareTrait;

    public function getContainerPublic()
    {
        return $this->getContainer();
    }
}
