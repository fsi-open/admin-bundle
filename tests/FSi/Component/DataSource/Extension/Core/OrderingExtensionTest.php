<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Tests\Extension\Core;

use FSi\Component\DataSource\Extension\Core\Ordering\OrderingExtension;
use FSi\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension;
use FSi\Component\DataSource\Extension\Core\Ordering\Driver\DriverExtension;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Field\FieldAbstractType;
use FSi\Component\DataSource\Event\DataSourceEvent;
use FSi\Component\DataSource\Event\FieldEvent;

/**
 * Tests for Ordering Extension.
 */
class OrderingExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Checks DataSource subscriber and storing of passed parameters.
     */
    public function testStoringParameters()
    {
        $extension = new OrderingExtension();
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSourceInterface', [], [$driver]);
        $field = $this->getMock('FSi\Component\DataSource\Field\FieldTypeInterface');
        $fieldExtension = new FieldExtension();

        $field
            ->expects($this->atLeastOnce())
            ->method('getExtensions')
            ->will($this->returnValue([$fieldExtension]))
        ;

        $datasource
            ->expects($this->any())
            ->method('getFields')
            ->will($this->returnValue(['test' => $field]))
        ;

        $datasource
            ->expects($this->any())
            ->method('getField')
            ->with('test')
            ->will($this->returnValue($field))
        ;

        $datasource
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('ds'))
        ;

        $subscribers = $extension->loadSubscribers();
        $subscriber = array_shift($subscribers);

        $parameters = [
            'ds'    => [
                OrderingExtension::PARAMETER_SORT    => [
                    'test'    => 'asc'
                ]
            ]
        ];

        $subscriber->preBindParameters(new DataSourceEvent\ParametersEventArgs($datasource, $parameters));

        //Assert that request parameters are properly stored in FieldExtension.
        $this->assertEquals(
            [
                'priority'    => 0,
                'direction'   => 'asc'
            ],
            $fieldExtension->getOrdering($field)
        );

        $event = new DataSourceEvent\ParametersEventArgs($datasource, []);
        $subscriber->postGetParameters($event);

        $this->assertEquals(
            $parameters,
            $event->getParameters()
        );
    }

    /**
     * Checks if sort order is properly calculated from default sorting options and parameters passed from user request.
     */
    public function testOrdering()
    {
        $self = $this;
        /**
         * Each test case consists of fields options definition, ordering parameters passed to datasource and expected fields
         * array which should be sorted in terms of priority of sorting results. Expected array contain sorting passed in
         * parameters first and then default sorting passed in options.
         */
        $input = [
            [
                'fields' => [
                    ['name' => 'field1'],
                    ['name' => 'field2'],
                    ['name' => 'field3'],
                ],
                'parameters' => [
                    'field1' => 'asc'
                ],
                'expected_ordering' => [
                    'field1' => 'asc'
                ],
                'expected_parameters' => [
                    'field1' => [
                        'ordering_ascending' => ['field1' => 'asc'],
                        'ordering_descending' => ['field1' => 'desc']
                    ],
                    'field2' => [
                        'ordering_ascending' => [
                            'field2' => 'asc',
                            'field1' => 'asc'
                        ],
                        'ordering_descending' => [
                            'field2' => 'desc',
                            'field1' => 'asc'
                        ]
                    ],
                    'field3' => [
                        'ordering_ascending' => [
                            'field3' => 'asc',
                            'field1' => 'asc'
                        ],
                        'ordering_descending' => [
                            'field3' => 'desc',
                            'field1' => 'asc'
                        ]
                    ],
                ]
            ],
            [
                'fields' => [
                    ['name' => 'field1'],
                    ['name' => 'field2'],
                    ['name' => 'field3'],
                ],
                'parameters'  => [
                    'field2' => 'asc',
                    'field1' => 'desc'
                ],
                'expected_ordering' => [
                    'field2' => 'asc',
                    'field1' => 'desc'
                ],
                'expected_parameters' => [
                    'field1' => [
                        'ordering_ascending' => [
                            'field1' => 'asc',
                            'field2' => 'asc'
                        ],
                        'ordering_descending' => [
                            'field1' => 'desc',
                            'field2' => 'asc'
                        ]
                    ],
                    'field2' => [
                        'ordering_ascending' => [
                            'field2' => 'asc',
                            'field1' => 'desc'
                        ],
                        'ordering_descending' => [
                            'field2' => 'desc',
                            'field1' => 'desc'
                        ]
                    ],
                    'field3' => [
                        'ordering_ascending' => [
                            'field3' => 'asc',
                            'field2' => 'asc',
                            'field1' => 'desc'
                        ],
                        'ordering_descending' => [
                            'field3' => 'desc',
                            'field2' => 'asc',
                            'field1' => 'desc'
                        ]
                    ],
                ]
            ],
            [
                'fields' => [
                    [
                        'name' => 'field1',
                        'options' => ['default_sort' => 'asc', 'default_sort_priority' => 1]
                    ],
                    [
                        'name' => 'field2',
                        'options' => ['default_sort' => 'desc', 'default_sort_priority' => 2]
                    ],
                    [
                        'name' => 'field3',
                        'options' => ['default_sort' => 'asc']
                    ],
                ],
                'parameters' => ['field3' => 'desc'],
                'expected_ordering' => [
                    'field3' => 'desc',
                    'field2' => 'desc',
                    'field1' => 'asc'
                ],
                'expected_parameters' => [
                    'field1' => [
                        'ordering_ascending' => [
                            'field1' => 'asc',
                            'field3' => 'desc'
                        ],
                        'ordering_descending' => [
                            'field1' => 'desc',
                            'field3' => 'desc'
                        ]
                    ],
                    'field2' => [
                        'ordering_ascending' => [
                            'field2' => 'asc',
                            'field3' => 'desc'
                        ],
                        'ordering_descending' => [
                            'field2' => 'desc',
                            'field3' => 'desc'
                        ]
                    ],
                    'field3' => [
                        'ordering_ascending' => ['field3' => 'asc'],
                        'ordering_descending' => ['field3' => 'desc']
                    ],
                ]
            ],
            [
                'fields' => [
                    [
                        'name' => 'field1',
                        'options' => ['default_sort' => 'asc', 'default_sort_priority' => 1]
                    ],
                    [
                        'name' => 'field2',
                        'options' => ['default_sort' => 'desc', 'default_sort_priority' => 2]
                    ],
                    [
                        'name' => 'field3',
                        'options' => ['default_sort' => 'asc']
                    ],
                ],
                'parameters' => [
                    'field1' => 'asc',
                    'field3' => 'desc'
                ],
                'expected_ordering' => [
                    'field1' => 'asc',
                    'field3' => 'desc',
                    'field2' => 'desc'
                ],
                'expected_parameters' => [
                    'field1' => [
                        'ordering_ascending' => [
                            'field1' => 'asc',
                            'field3' => 'desc'
                        ],
                        'ordering_descending' => [
                            'field1' => 'desc',
                            'field3' => 'desc'
                        ]
                    ],
                    'field2' => [
                        'ordering_ascending' => [
                            'field2' => 'asc',
                            'field1' => 'asc',
                            'field3' => 'desc'
                        ],
                        'ordering_descending' => [
                            'field2' => 'desc',
                            'field1' => 'asc',
                            'field3' => 'desc'
                        ]
                    ],
                    'field3' => [
                        'ordering_ascending' => [
                            'field3' => 'asc',
                            'field1' => 'asc'
                        ],
                        'ordering_descending' => [
                            'field3' => 'desc',
                            'field1' => 'asc'
                        ]
                    ],
                ]
            ],
        ];

        foreach ($input as $case) {
            $datasource = $this->getMock('FSi\Component\DataSource\DataSourceInterface');

            $fieldExtension = new FieldExtension();

            $fields = [];
            foreach ($case['fields'] as $fieldData) {
                //Using fake class object instead of mock object is helpfull because we need functionality from AbstractFieldType.
                $field = new FakeFieldType();
                $field->setName($fieldData['name']);
                $field->setDataSource($datasource);
                $field->addExtension($fieldExtension);
                if (isset($fieldData['options'])) {
                    $field->setOptions($fieldData['options']);
                }
                $fields[$fieldData['name']] = $field;
            }

            $datasource
                ->expects($this->atLeastOnce())
                ->method('getName')
                ->will($this->returnValue('ds'))
            ;

            $datasource
                ->expects($this->any())
                ->method('getFields')
                ->will($this->returnValue($fields))
            ;

            $datasource
                ->expects($this->any())
                ->method('getField')
                ->will($this->returnCallback(function () use ($fields) {
                    return $fields[func_get_arg(0)];
                }))
            ;

            $datasource
                ->expects($this->any())
                ->method('getParameters')
                ->will($this->returnValue(['ds' => [OrderingExtension::PARAMETER_SORT => $case['parameters']]]))
            ;

            $extension = new OrderingExtension();
            $subscribers = $extension->loadSubscribers();
            $subscriber = array_shift($subscribers);
            $subscriber->preBindParameters(new DataSourceEvent\ParametersEventArgs(
                $datasource,
                ['ds' => [OrderingExtension::PARAMETER_SORT => $case['parameters']]]
            ));

            //We use fake driver extension instead of specific driver extension because we want to test common DriverExtension functionality.
            $driverExtension = new FakeDriverExtension();
            $result = $driverExtension->sort($fields);
            $this->assertSame($case['expected_ordering'], $result);

            foreach ($fields as $field) {
                $view = $this->getMock('FSi\Component\DataSource\Field\FieldViewInterface');

                $view
                    ->expects($this->exactly(5))
                    ->method('setAttribute')
                    ->will($this->returnCallback(function ($attribute, $value) use ($self, $field, $case) {
                        switch ($attribute) {
                            case 'sorted_ascending':
                                $self->assertEquals(
                                    (key($case['parameters']) == $field->getName()) && (current($case['parameters']) == 'asc'),
                                    $value
                                );
                                break;

                            case 'sorted_descending':
                                $self->assertEquals(
                                    (key($case['parameters']) == $field->getName()) && (current($case['parameters']) == 'desc'),
                                    $value
                                );
                                break;

                            case 'parameters_sort_ascending':
                                $self->assertSame(
                                    [
                                        'ds' => [
                                            OrderingExtension::PARAMETER_SORT => $case['expected_parameters'][$field->getName()]['ordering_ascending']
                                        ]
                                    ],
                                    $value
                                );
                                break;

                            case 'parameters_sort_descending':
                                $self->assertSame(
                                    [
                                        'ds' => [
                                            OrderingExtension::PARAMETER_SORT => $case['expected_parameters'][$field->getName()]['ordering_descending']
                                        ]
                                    ],
                                    $value
                                );
                                break;
                        }
                    }))
                ;

                $fieldExtension->postBuildView(new FieldEvent\ViewEventArgs($field, $view));
            }
        }
    }
}

class FakeFieldType extends FieldAbstractType
{
    public function getType()
    {
        return 'fake';
    }
}

class FakeDriverExtension extends DriverExtension
{
    public function getExtendedDriverTypes()
    {
        return [];
    }

    public function sort(array $fields)
    {
        return $this->sortFields($fields);
    }
}
