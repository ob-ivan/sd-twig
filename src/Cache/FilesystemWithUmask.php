<?php
namespace SD\TwigBridge\Cache;

use Twig_Cache_Filesystem;

class FilesystemWithUmask extends Twig_Cache_Filesystem
{
    public function write($key, $content)
    {
        $old = umask(0002);
        parent::write($key, $content);
        umask($old);
        chmod($key, 0775);
    }
}
