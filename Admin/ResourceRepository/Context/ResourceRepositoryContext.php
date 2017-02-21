<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextAbstract;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Element as ResourceRepositoryElement;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceRepositoryContext extends ContextAbstract
{
    /**
     * @var ResourceRepository\Element
     */
    protected $element;

    /**
     * @var ResourceFormBuilder
     */
    private $resourceFormBuilder;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @param HandlerInterface[]|$requestHandlers
     * @param ResourceFormBuilder $resourceFormBuilder
     */
    public function __construct($requestHandlers, ResourceFormBuilder $resourceFormBuilder)
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
        return $element instanceof ResourceRepositoryElement;
    }
}
