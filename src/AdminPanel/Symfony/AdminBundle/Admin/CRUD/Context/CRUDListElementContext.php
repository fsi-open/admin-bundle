<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\CRUDElement;
use AdminPanel\Symfony\AdminBundle\Admin\Element;

class CRUDListElementContext extends ListElementContext
{
    /**
     * @var string
     */
    private $listTemplate;

    /**
     * @param HandlerInterface[]|array $requestHandlers
     * @param string $listTemplate
     */
    public function __construct(array $requestHandlers, $listTemplate)
    {
        parent::__construct($requestHandlers);

        $this->listTemplate = $listTemplate;
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
        return $this->element->hasOption('template_list') ?
            $this->element->getOption('template_list') : $this->listTemplate;
    }
}
