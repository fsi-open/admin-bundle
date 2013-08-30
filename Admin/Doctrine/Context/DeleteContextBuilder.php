<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use FSi\Bundle\AdminBundle\Exception\InvalidEntityIdException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class DeleteContextBuilder implements ContextBuilderInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var \Symfony\Component\Routing\Router
     */
    protected $router;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $factory;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param EventDispatcher $dispatcher
     * @param \Symfony\Component\Routing\Router $router
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    public function __construct(EventDispatcher $dispatcher, Router $router, FormFactoryInterface $factory)
    {
        $this->dispatcher = $dispatcher;
        $this->router = $router;
        $this->factory = $factory;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($route, ElementInterface $element)
    {
        if ($route !== $this->getSupportedRoute()) {
            return false;
        }

        if ($element instanceof CRUDElement) {
            $data = $this->getData($element);

            if (!count($data)) {
                throw new ContextBuilderException("There must be at least one object to execute delete action");
            }

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function buildContext(ElementInterface $element)
    {
        /* @var $element \FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement */
        $context = new DeleteContext($this->dispatcher, $element, $this->router, $this->factory, $this->getData($element));

        return $context;
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_crud_delete';
    }

    protected function getData(ElementInterface $element)
    {
        $data = array();
        $indexes = $this->request->request->get('indexes', array());
        foreach ($indexes as $index) {
            $entity = $element->getDataIndexer()->getData($index);

            if (!isset($entity)) {
                throw new InvalidEntityIdException(sprintf('Cant find entity with id %s', $index));
            }

            $data[] = $entity;
        }

        return $data;
    }
}