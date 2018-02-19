<?php
namespace tests\DependencyInjection;

use Twig_Cache_Filesystem;

class MockCache extends Twig_Cache_Filesystem
{
    private $directory;

    public function __construct($directory, $options = 0)
    {
        parent::__construct($directory, $options);
        $this->directory = $directory;
    }

    public function getPath()
    {
        return $this->directory;
    }
}
