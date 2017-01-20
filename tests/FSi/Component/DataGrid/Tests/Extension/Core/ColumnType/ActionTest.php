<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Action;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class ActionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FSi\Component\DataGrid\Extension\Core\ColumnType\Action
     */
    private $column;

    public function setUp()
    {
        $column = new Action();
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    /**
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function testFilterValueEmptyActionsOptionType()
    {
        $this->column->setOption('actions', 'boo');
        $this->column->filterValue(array());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterValueInvalidActionInActionsOption()
    {
        $this->column->setOption('actions', array('edit' => 'asasdas'));
        $this->column->filterValue(array());
    }

    public function testFilterValueRequiredActionInActionsOption()
    {
        $this->column->setOption('actions', array(
            'edit' => array(
                'uri_scheme' => '/test/%s',
            )
        ));

        $this->assertSame(
            array(
                'edit' => array(
                    'url' => '/test/bar',
                    'field_mapping_values' => array(
                        'foo' => 'bar'
                    )
                )
            ),
            $this->column->filterValue(array(
                'foo' => 'bar'
            ))
        );
    }

    public function testFilterValueAvailableActionInActionsOption()
    {
        $this->column->setOption('actions', array(
            'edit' => array(
                'uri_scheme' => '/test/%s',
                'domain' => 'fsi.pl',
                'protocol' => 'https://',
                'redirect_uri' => 'http://onet.pl/'
            )
        ));

        $this->assertSame(
            array(
                'edit' => array(
                    'url' => 'https://fsi.pl/test/bar?redirect_uri=' . urlencode('http://onet.pl/'),
                    'field_mapping_values' => array(
                        'foo' => 'bar'
                    )
                )
            ),
            $this->column->filterValue(array(
                'foo' => 'bar'
            ))
        );
    }
}
