<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Tests\Extension\Symfony;

use FSi\Component\DataSource\Extension\Symfony\Form\Field\FormFieldExtension;
use ReflectionMethod;

class FormFieldExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildBooleanFormWhenOptionsProvided()
    {
        $formFactory = $this->getMockBuilder('Symfony\Component\Form\FormFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $formFielExtension = new FormFieldExtension($formFactory);

        $method = new ReflectionMethod(
            'FSi\Component\DataSource\Extension\Symfony\Form\Field\FormFieldExtension',
            'buildBooleanForm'
        );
        $method->setAccessible(true);

        $field = $this->getMock('FSi\Component\DataSource\Driver\Collection\Extension\Core\Field\Boolean');

        $field->expects($this->once())
            ->method('getname')
            ->will($this->returnValue('name'));

        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();

        $form->expects($this->exactly(1))->method('add')
            ->with(
                $this->equalTo('name'),
                $this->equalTo('choice'),
                $this->equalTo(
                    [
                        'choices' => [
                            '1' => 'tak',
                            '0' => 'nie',
                        ],
                        'multiple' => false,
                        'empty_value' => ''
                    ]
                )
            );

        $options =  [
            'choices' => [
                '1' => 'tak',
                '0' => 'nie'
            ]
        ];

        $method->invoke(
            $formFielExtension,
            $form,
            $field,
            $options
        );
    }

    public function testBuildBooleanFormWhenOptionsNotProvided()
    {
        $formFactory = $this->getMockBuilder('Symfony\Component\Form\FormFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $formFielExtension = new FormFieldExtension($formFactory);

        $method = new ReflectionMethod(
            'FSi\Component\DataSource\Extension\Symfony\Form\Field\FormFieldExtension',
            'buildBooleanForm'
        );
        $method->setAccessible(true);

        $field = $this->getMock('FSi\Component\DataSource\Driver\Collection\Extension\Core\Field\Boolean');

        $field->expects($this->once())
            ->method('getname')
            ->will($this->returnValue('name'));

        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();

        $form->expects($this->exactly(1))->method('add')
            ->with(
                $this->equalTo('name'),
                $this->equalTo('choice'),
                $this->equalTo(
                    [
                        'choices' => [
                            '1' => 'yes',
                            '0' => 'no',
                        ],
                        'multiple' => false,
                        'empty_value' => ''
                    ]
                )
            );

        $options =  [];

        $method->invoke(
            $formFielExtension,
            $form,
            $field,
            $options
        );
    }
}
