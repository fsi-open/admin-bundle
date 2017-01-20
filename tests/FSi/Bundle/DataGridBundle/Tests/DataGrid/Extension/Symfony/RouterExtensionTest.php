<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\Tests\DataGrid\Extension\Symfony;

use FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\RouterExtension;

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
