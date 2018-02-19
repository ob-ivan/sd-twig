<?php
namespace tests\DependencyInjection;

use Twig_Loader_Filesystem;

class MockLoader extends Twig_Loader_Filesystem
{
    private $extra;

    public function __construct($paths = array(), $rootPath = null, $extra = null)
    {
        parent::__construct($paths, $rootPath);
        $this->extra = $extra;
    }

    public function getExtra()
    {
        return $this->extra;
    }
}
