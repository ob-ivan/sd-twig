<?php
namespace SD\Twig\Loader;

use Twig_Loader_Filesystem;

class FilesystemWithExtension extends Twig_Loader_Filesystem
{
    private $extension;

    public function __construct($paths = array(), $rootPath = null, $extension = '.twig')
    {
        parent::__construct($paths, $rootPath);
        $this->extension = $extension;
    }

    protected function findTemplate($name, $throw = true)
    {
        if (substr($name, -strlen($this->extension)) !== $this->extension) {
            $name .= $this->extension;
        }
        return parent::findTemplate($name, $throw);
    }
}
