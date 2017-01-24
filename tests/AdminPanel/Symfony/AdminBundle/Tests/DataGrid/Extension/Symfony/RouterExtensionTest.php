<?php

namespace AdminPanel\Symfony\AdminBundleBundle\Tests\DataGrid\Extension\Symfony;

use AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Symfony\RouterExtension;

class RouterExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testSymfonyExtension()
    {
        $router = $this->getMock('Symfony\Component\Routing\RouterInterface');
        $requestStack = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');
        $extension = new RouterExtension($router, $requestStack);

        $this->assertTrue($extension->hasColumnType('action'));
    }
}
