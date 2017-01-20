<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\Tests\DataGrid\Extension\View\ColumnTypeExtension;

use FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnTypeExtension\FormExtension;
use FSi\Bundle\DataGridBundle\DataGrid\Extension\View\ColumnTypeExtension\BooleanColumnExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Boolean;

class BooleanColumnExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testColumnOptions()
    {
        $column = new Boolean();
        $formExtension = new FormExtension($this->getFormFactory());
        $formExtension->initOptions($column);
        $extension = new BooleanColumnExtension($this->getTranslator());
        $extension->initOptions($column);
        $options = $column->getOptionsResolver()->resolve();

        $this->assertEquals('YES', $options['true_value']);
        $this->assertEquals('NO', $options['false_value']);
    }

    private function getTranslator()
    {
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');

        $translator->expects($this->at(0))
            ->method('trans')
            ->with('datagrid.boolean.yes', array(), 'DataGridBundle')
            ->will($this->returnValue('YES'));

        $translator->expects($this->at(1))
            ->method('trans')
            ->with('datagrid.boolean.no', array(), 'DataGridBundle')
            ->will($this->returnValue('NO'));

        return $translator;
    }

    private function getFormFactory()
    {
        return $this->getMock('Symfony\Component\Form\FormFactoryInterface');
    }
}
