<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Tests;

use FSi\Bundle\AdminBundle\FSiAdminBundle;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class FSiAdminBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerBuilder');
        $container->expects($this->once())
            ->method('addCompilerPass')
            ->with($this->isInstanceOf('\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface'));

        $bundle = new FSiAdminBundle();
        $bundle->build($container);
    }
}