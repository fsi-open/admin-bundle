<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\Tests\DependencyInjection;

use FSi\Bundle\DataGridBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultOptions()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array('fsi_data_grid' => array()));

        $this->assertSame(
            $config,
            self::getBundleDefaultOptions()
        );
    }

    public function testDeprecatedTemplateOption()
    {
        $this->setExpectedException('PHPUnit_Framework_Error_Deprecated');

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array('fsi_data_grid' => array(
            'twig' => array(
                'template' => 'custom_datagrid.html.twig'
            )
        )));

        $this->assertSame(
            $config,
            self::getBundleDefaultOptions()
        );
    }

    public function testTemplateOption()
    {
        \PHPUnit_Framework_Error_Deprecated::$enabled = false;

        $processor = new Processor();
        $config = @$processor->processConfiguration(new Configuration(), array('fsi_data_grid' => array(
            'twig' => array(
                'template' => 'custom_datagrid.html.twig'
            )
        )));

        $this->assertSame(
            $config,
            array(
                'twig' => array(
                    'enabled' => true,
                    'themes' => array('custom_datagrid.html.twig')
                ),
                'yaml_configuration' => true
            )
        );
    }

    public function testThemesOption()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array('fsi_data_grid' => array(
            'twig' => array(
                'themes' => array('custom_datagrid.html.twig')
            )
        )));

        $this->assertSame(
            $config,
            array(
                'twig' => array(
                    'themes' => array('custom_datagrid.html.twig'),
                    'enabled' => true
                ),
                'yaml_configuration' => true
            )
        );
    }

    public static function getBundleDefaultOptions()
    {
        return array(
            'yaml_configuration' => true,
            'twig' => array(
                'enabled' => true,
                'themes' => array('datagrid.html.twig')
            )
        );
    }
}
