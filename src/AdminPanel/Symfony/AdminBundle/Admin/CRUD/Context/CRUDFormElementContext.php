<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\CRUDElement;
use AdminPanel\Symfony\AdminBundle\Admin\Element;

class CRUDFormElementContext extends FormElementContext
{
    /**
     * @var string
     */
    private $formTemplate;

    /**
     * @param HandlerInterface[]|array $requestHandlers
     * @param string $formTemplate
     */
    public function __construct(array $requestHandlers, $formTemplate)
    {
        parent::__construct($requestHandlers);

        $this->formTemplate = $formTemplate;
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsElement(Element $element)
    {
        return $element instanceof CRUDElement;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->hasOption('template_form') ?
            $this->element->getOption('template_form') : $this->formTemplate;
    }
}
