<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Context\ContextAbstract;
use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository;
use AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\Request;

class ResourceRepositoryContext extends ContextAbstract
{
    /**
     * @var ResourceRepository\Element
     */
    protected $element;

    /**
     * @var ResourceRepository\ResourceFormBuilder
     */
    private $resourceFormBuilder;

    /**
     * @var \Symfony\Component\Form\Form
     */
    private $form;

    /**
     * @param HandlerInterface[]|$requestHandlers
     * @param ResourceRepository\ResourceFormBuilder $resourceFormBuilder
     */
    function __construct($requestHandlers, ResourceRepository\ResourceFormBuilder $resourceFormBuilder)
    {
        parent::__construct($requestHandlers);

        $this->resourceFormBuilder = $resourceFormBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function setElement(Element $element)
    {
        $this->element = $element;
        $this->form = $this->resourceFormBuilder->build($this->element);
    }

    /**
     * @return boolean
     */
    public function hasTemplateName()
    {
        return $this->element->hasOption('template');
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->element->getOption('template');
    }

    /**
     * @return array
     */
    public function getData()
    {
        return array(
            'form' => $this->form->createView(),
            'element' => $this->element
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function createEvent(Request $request)
    {
        return new FormEvent($this->element, $request, $this->form);
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_resource';
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsElement(Element $element)
    {
        return $element instanceof ResourceRepository\Element;
    }
}
