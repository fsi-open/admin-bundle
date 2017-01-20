<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Symfony;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @deprecated This class is deprecated since version 1.2. Please use fsi/datagrid-bundle and its
 * FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\FormExtension
 */
class FormExtension extends DataGridAbstractExtension
{
    /**
     * FormFactory used by extension to build forms.
     *
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypesExtensions()
    {
        return array(
            new ColumnTypeExtension\FormExtension($this->formFactory),
        );
    }
}
