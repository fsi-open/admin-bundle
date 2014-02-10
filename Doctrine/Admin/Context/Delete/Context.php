<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class Context implements ContextInterface
{
    /**
     * @var HandlerInterface
     */
    private $requestHandlers;

    /**
     * @var \FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement
     */
    protected $element;

    /**
     * @var \Symfony\Component\Form\Form
     */
    protected $form;

    /**
     * @var array
     */
    protected $indexes;

    /**
     * @param array $requestHandlers
     * @param FormFactoryInterface $factory
     */
    public function __construct(
        array $requestHandlers,
        FormFactoryInterface $factory
    ) {
        $this->factory = $factory;
        $this->requestHandlers = $requestHandlers;
        $this->form = $this->factory->createNamed('delete', 'form');
    }

    /**
     * @param CRUDElement $element
     */
    public function setElement(CRUDElement $element)
    {
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(Request $request)
    {
        $event = new FormEvent($this->element, $request, $this->form);
        $this->indexes = $request->request->get('indexes', array());

        foreach ($this->requestHandlers as $handler) {
            $response = $handler->handleRequest($event, $request);
            if (isset($response)) {
                return $response;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return $this->element->hasOption('template_crud_delete');
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->getOption('template_crud_delete');
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return array(
            'element' => $this->element,
            'indexes' => $this->indexes,
            'form' => $this->form->createView(),
        );
    }
}
