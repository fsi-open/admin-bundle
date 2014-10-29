<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ResourceRepositoryContext implements ContextInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $requestHandlers;

    /**
     * @var GenericResourceElement
     */
    private $element;

    /**
     * @var \FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder
     */
    private $resourceFormBuilder;

    /**
     * @var \Symfony\Component\Form\Form
     */
    private $form;

    /**
     * @param $requestHandlers
     * @param \FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder $resourceFormBuilder
     */
    function __construct($requestHandlers, ResourceFormBuilder $resourceFormBuilder)
    {
        $this->requestHandlers = $requestHandlers;
        $this->resourceFormBuilder = $resourceFormBuilder;
    }

    /**
     * @param GenericResourceElement $element
     */
    public function setElement(GenericResourceElement $element)
    {
        $this->element = $element;
        $this->form = $this->resourceFormBuilder->build($this->element);
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request)
    {
        $event = new FormEvent($this->element, $request, $this->form);

        foreach ($this->requestHandlers as $handler) {
            $response = $handler->handleRequest($event, $request);
            if (isset($response)) {
                return $response;
            }
        }
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
            'element' => $this->element,
            'title' => $this->element->getOption('title')
        );
    }
}
