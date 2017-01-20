<?php

/**
 * (c) FSi Sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataSourceBundle\Tests\DataSource\Extension\Symfony\Form\Field;

use FSi\Bundle\DataSourceBundle\DataSource\Extension\Symfony\Form\Field\FormFieldExtension;

class FormFieldExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testFormFieldExtensionForIsNullComparison()
    {
        $optionResolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolver');
        $optionResolver->expects($this->once())
            ->method('setDefaults')
            ->with(array(
                'form_options' => array(
                    'choices' => array(
                        'null' => 'is_null_translated',
                        'no_null' => 'is_not_null_translated'
                    )
                )
            ));

        $fieldType = $this->getMock('FSi\Component\DataSource\Field\FieldTypeInterface');
        $fieldType->expects($this->atLeastOnce())
            ->method('getOptionsResolver')
            ->will($this->returnValue($optionResolver));
        $fieldType->expects($this->atLeastOnce())
            ->method('getComparison')
            ->will($this->returnValue('isNull'));

        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translator->expects($this->any())
            ->method('trans')
            ->will($this->returnCallback(function ($id, array $params, $translation_domain) {
                if ($translation_domain != 'DataSourceBundle') {
                    throw new \RuntimeException(sprintf('Unknown translation domain %s', $translation_domain));
                }
                switch ($id) {
                    case 'datasource.form.choices.is_null':
                        return 'is_null_translated';
                    case 'datasource.form.choices.is_not_null':
                        return 'is_not_null_translated';
                    default:
                        throw new \RuntimeException(sprintf('Unknown translation id %s', $id));
                }
            }));

        $extension = new FormFieldExtension($translator);

        $this->assertSame(
            array('text', 'number', 'date', 'time', 'datetime', 'entity', 'boolean'),
            $extension->getExtendedFieldTypes()
        );

        $extension->initOptions($fieldType);
    }

    public function testFormFieldExtensionForBooleanType()
    {
        $optionResolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolver');
        $optionResolver->expects($this->once())
            ->method('setDefaults')
            ->with(array(
                'form_options' => array(
                    'choices' => array(
                        '1' => 'yes_translated',
                        '0' => 'no_translated'
                    )
                )
            ));

        $fieldType = $this->getMock('FSi\Component\DataSource\Field\FieldTypeInterface');
        $fieldType->expects($this->atLeastOnce())
            ->method('getOptionsResolver')
            ->will($this->returnValue($optionResolver));
        $fieldType->expects($this->atLeastOnce())
            ->method('getType')
            ->will($this->returnValue('boolean'));

        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translator->expects($this->any())
            ->method('trans')
            ->will($this->returnCallback(function ($id, array $params, $translation_domain) {
                if ($translation_domain != 'DataSourceBundle') {
                    throw new \RuntimeException(sprintf('Unknown translation domain %s', $translation_domain));
                }
                switch ($id) {
                    case 'datasource.form.choices.yes':
                        return 'yes_translated';
                    case 'datasource.form.choices.no':
                        return 'no_translated';
                    default:
                        throw new \RuntimeException(sprintf('Unknown translation id %s', $id));
                }
            }));

        $extension = new FormFieldExtension($translator);

        $this->assertSame(
            array('text', 'number', 'date', 'time', 'datetime', 'entity', 'boolean'),
            $extension->getExtendedFieldTypes()
        );

        $extension->initOptions($fieldType);
    }
}
