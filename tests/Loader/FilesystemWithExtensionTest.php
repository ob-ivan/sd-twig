<?php
namespace tests\Loader;

use PHPUnit\Framework\TestCase;
use SD\Twig\Loader\FilesystemWithExtension;

class FilesystemWithExtensionTest extends TestCase
{
    public function testDefault()
    {
        $loader = new FilesystemWithExtension('templates', __DIR__);
        $this->assertTrue($loader->exists('exists'));
    }
}
