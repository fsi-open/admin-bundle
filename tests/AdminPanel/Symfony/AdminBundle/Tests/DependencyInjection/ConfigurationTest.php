<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundleBundle\Tests\DependencyInjection;

use AdminPanel\Symfony\AdminBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultOptions()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(
            new Configuration(), [
                'fsi_admin' => [
                    'data_grid' => []
                ]
        ]);

        $this->assertSame(
            $config,
            self::getBundleDefaultOptions()
        );
    }

    public function testDeprecatedTemplateOption()
    {
        $this->setExpectedException('PHPUnit_Framework_Error_Deprecated');

        $processor = new Processor();
        $config = $processor->processConfiguration(
            new Configuration(),
            [
                'fsi_admin' => [
                    'data_grid' => [
                        'twig' => [
                            'template' => 'custom_datagrid.html.twig'
                        ]
                    ]
                ]
            ]);

        $this->assertSame(
            $config,
            self::getBundleDefaultOptions()
        );
    }

    public function testTemplateOption()
    {
        \PHPUnit_Framework_Error_Deprecated::$enabled = false;

        $processor = new Processor();
        $config = @$processor->processConfiguration(
            new Configuration(),
            [
                'fsi_admin' => [
                    'data_grid' => [
                        'twig' => [
                            'template' => 'custom_datagrid.html.twig'
                        ]
                    ]
                ]
            ])
        ;

        $this->assertSame(
            $config['data_grid'],
            [
                'twig' => [
                    'enabled' => true,
                    'themes' => ['custom_datagrid.html.twig']
                ],
                'yaml_configuration' => true
            ]
        );
    }

    public function testThemesOption()
    {
        $processor = new Processor();
        $config = @$processor->processConfiguration(
            new Configuration(),
            [
                'fsi_admin' => [
                    'data_grid' => [
                        'twig' => [
                            'themes' => ['custom_datagrid.html.twig']
                        ]
                    ]
                ]
            ])
        ;

        $this->assertSame(
            $config['data_grid'],
            [
                'twig' => [
                    'themes' => ['custom_datagrid.html.twig'],
                    'enabled' => true
                ],
                'yaml_configuration' => true
            ]
        );
    }

    public static function getBundleDefaultOptions()
    {
        return [
            'data_grid' => [
                'yaml_configuration' => true,
                'twig' => [
                    'enabled' => true,
                    'themes' => ['datagrid.html.twig']
                ]
            ],
            'default_locale' => '%locale%',
            'locales' => ['%locale%'],
            'menu_config_path' => '%kernel.root_dir%/config/admin_menu.yml',
            'templates' => [
                'base' => '@AdminPanel/base.html.twig',
                'index_page' => '@AdminPanel/Admin/index.html.twig',
                'list' => '@AdminPanel/List/list.html.twig',
                'form' => '@AdminPanel/Form/form.html.twig',
                'crud_list' => '@AdminPanel/CRUD/list.html.twig',
                'crud_form' => '@AdminPanel/Form/form.html.twig',
                'resource' => '@AdminPanel/Resource/resource.html.twig',
                'display' => '@AdminPanel/Display/display.html.twig',
                'datagrid_theme' => '@AdminPanel/CRUD/datagrid.html.twig',
                'datasource_theme' => '@AdminPanel/CRUD/datasource.html.twig',
                'form_theme' => '@AdminPanel/Form/form_theme.html.twig'
            ],
            'annotations' => [
                'dirs' => []
            ],
            'data_source' => [
                'yaml_configuration' => true,
                'twig' => [
                    'enabled' => true,
                    'template' => 'datasource.html.twig'
                ]
            ],
        ];
    }
}
