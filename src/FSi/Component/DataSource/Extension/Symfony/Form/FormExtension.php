<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Extension\Symfony\Form;

use FSi\Component\DataSource\DataSourceAbstractExtension;
use Symfony\Component\Form\FormFactory;

/**
 * Form extension builds Symfony form for given datasource fields.
 *
 * Extension also maintains replacing parameters that came into request into proper form,
 * replacing these parameters into scalars while getting parameters and sets proper
 * options to view.
 */
class FormExtension extends DataSourceAbstractExtension
{
    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;

    /**
     * @param \Symfony\Component\Form\FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function loadDriverExtensions()
    {
        return array(
            new Driver\DriverExtension($this->formFactory),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return array(
            new EventSubscriber\Events(),
        );
    }
}
