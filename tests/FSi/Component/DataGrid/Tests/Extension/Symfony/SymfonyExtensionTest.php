<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Symfony;

use FSi\Component\DataGrid\Extension\Symfony\SymfonyExtension;

class SymfonyExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testSymfonyExtension()
    {
        if (!interface_exists('Symfony\Component\DependencyInjection\ContainerInterface')) {
            $this->markTestSkipped('Symfony\Component\DependencyInjection\ContainerInterface required for testSymfonyExtension');
        }

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $extension = new SymfonyExtension($container);

        $this->assertTrue($extension->hasColumnType('action'));
    }
}
