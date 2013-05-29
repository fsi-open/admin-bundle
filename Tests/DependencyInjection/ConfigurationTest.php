<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Test\DependencyInjection;

use FSi\Bundle\AdminBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultOptions()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array());

        $this->assertSame(
            $config,
            self::getBundleDefaultOptions()
        );
    }

    public static function getBundleDefaultOptions()
    {
        return array(
            'templates' => array(
                'base' => '@FSiAdmin/base.html.twig',
                'index_page' => '@FSiAdmin/Admin/index.html.twig',
                'admin_navigationtop' => '@FSiAdmin/Admin/navigationtop.html.twig',
                'admin_navigationleft' => '@FSiAdmin/Admin/navigationleft.html.twig',
                'crud_list' => '@FSiAdmin/CRUD/list.html.twig',
                'crud_create' => '@FSiAdmin/CRUD/create.html.twig',
                'crud_edit' => '@FSiAdmin/CRUD/edit.html.twig',
                'crud_delete' => '@FSiAdmin/CRUD/delete.html.twig',
                'datagrid_theme' => '@FSiAdmin/CRUD/datagrid.html.twig',
                'datasource_theme' => '@FSiAdmin/CRUD/datasource.html.twig',
                'edit_form_theme' => '@FSiAdmin/CRUD/form.html.twig',
                'create_form_theme' => '@FSiAdmin/CRUD/form.html.twig',
                'delete_form_theme' => '@FSiAdmin/CRUD/form.html.twig'
            ),
            'groups' => array()
        );
    }
}