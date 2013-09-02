<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ResourceEvents;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\ResourceInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Router;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ResourceContext implements ContextInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var \FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement
     */
    protected $element;

    /**
     * @var \FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder
     */
    protected $builder;

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    protected $formFactory;

    /**
     * @var \Symfony\Component\Routing\Router
     */
    protected $router;

    /**
     * @var \Symfony\Component\Form\Form
     */
    protected $form;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     * @param \FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement $element
     * @param \FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder $builder
     * @param \Symfony\Component\Form\FormFactory $formFactory
     * @param \Symfony\Component\Routing\Router $router
     */
    public function __construct(EventDispatcher $dispatcher, ResourceElement $element, MapBuilder $builder,
        FormFactory $formFactory, Router $router)
    {
        $this->dispatcher = $dispatcher;
        $this->element = $element;
        $this->builder = $builder;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->createForm();
    }

    /**
     * @param Request $request
     * @throws \FSi\Bundle\AdminBundle\Exception\ContextBuilderException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(Request $request)
    {
        $event = new AdminEvent($this->element, $request);
        $this->dispatcher->dispatch(ResourceEvents::RESOURCE_CONTEXT_POST_CREATE, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        if ($request->isMethod('POST')) {
            $this->dispatcher->dispatch(ResourceEvents::RESOURCE_FORM_REQUEST_PRE_SUBMIT, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $this->form->submit($request);

            $this->dispatcher->dispatch(ResourceEvents::RESOURCE_FORM_REQUEST_POST_SUBMIT, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            if ($this->form->isValid()) {
                $data = $this->form->getData();

                $this->dispatcher->dispatch(ResourceEvents::RESOURCE_PRE_SAVE, $event);
                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                foreach ($data as $object) {
                    $this->element->getObjectManager()->persist($object);
                }

                $this->element->getObjectManager()->flush();

                $this->dispatcher->dispatch(ResourceEvents::RESOURCE_POST_SAVE, $event);
                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                return new RedirectResponse($this->router->generate('fsi_admin_resource', array(
                    'element' => $this->element->getId(),
                )));
            }
        }

        $this->dispatcher->dispatch(ResourceEvents::RESOURCE_RESPONSE_PRE_RENDER, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        return null;
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

    protected function createForm()
    {
        $resources = $this->getResourceGroup($this->element);

        if (!is_array($resources)) {
            throw new ContextBuilderException(sprintf('%s its not a resource group key', $this->element->getKey()));
        }

        $builder = $this->formFactory->createBuilder(
            'form',
            $this->createFormData($resources),
            $this->element->getResourceFormOptions()
        );

        $this->buildForm($builder, $resources);
        $this->form = $builder->getForm();
    }

    /**
     * @param ResourceElement $element
     * @return mixed
     */
    protected function getResourceGroup(ResourceElement $element)
    {
        $map = $this->builder->getMap();

        $parts = explode('.', $element->getKey());
        $propertyPath = '';

        foreach ($parts as $part) {
            $propertyPath .= sprintf("[%s]", $part);
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        return $accessor->getValue($map, $propertyPath);
    }

    /**
     * @param array $resources
     * @return array
     */
    protected function createFormData(array $resources)
    {
        $data = array();

        foreach ($resources as $resource) {
            if ($resource instanceof ResourceInterface) {
                $data[$this->normalizeKey($resource->getName())] = $this->element->getRepository()->get($resource->getName());
            }
        }

        return $data;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $resources
     */
    protected function buildForm(FormBuilderInterface $builder, array $resources)
    {
        foreach ($resources as $resource) {
            if ($resource instanceof ResourceInterface) {
                $builder->add($this->normalizeKey($resource->getName()), 'resource', array(
                    'resource_key' => $resource->getName(),
                ));
            }
        }

    }

    /**
     * @param $key
     * @return mixed
     */
    protected function normalizeKey($key)
    {
        return str_replace('.', '_', $key);
    }
}