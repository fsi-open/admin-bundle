<?php

namespace AdminPanel\Symfony\AdminBundleBundle\Tests\DataGrid\Extension\Symfony;

use AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Symfony\FormExtension;

class FormExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testSymfonyFormExtension()
    {
        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $extension = new FormExtension($formFactory);

        $this->assertFalse($extension->hasColumnType('foo'));
        $this->assertTrue($extension->hasColumnTypeExtensions('text'));
    }
}
