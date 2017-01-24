<?php

declare(strict_types=1);

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
        return [
            new Driver\DriverExtension($this->formFactory),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return [
            new EventSubscriber\Events(),
        ];
    }
}
