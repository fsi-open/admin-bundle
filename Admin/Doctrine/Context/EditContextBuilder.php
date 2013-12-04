<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class EditContextBuilder implements ContextBuilderInterface
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
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \Symfony\Component\Routing\Router $router
     */
    public function __construct(EventDispatcherInterface $dispatcher, Router $router)
    {
        $this->dispatcher = $dispatcher;
        $this->router = $router;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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
            if (!$element->getOption('allow_edit')) {
                throw new ContextBuilderException(sprintf("%s does not allow to edit objects", $element->getName()));
            }

            if (!$this->hasObject($element)) {
                throw new ContextBuilderException(sprintf("Cant find object with id %s", $this->getObjectId()));
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
        $context = new EditContext($this->dispatcher, $element, $this->router, $this->getObject($element));

        return $context;
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_crud_edit';
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @return bool
     */
    protected function hasObject(ElementInterface $element)
    {
        $data = $element->getDataIndexer()->getData($this->getObjectId());

        return isset($data);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @return mixed
     */
    protected function getObject(ElementInterface $element)
    {
        $data = $element->getDataIndexer()->getData($this->getObjectId());

        return $data;
    }

    /**
     * @return mixed
     */
    protected function getObjectId()
    {
        return $this->request->get('id', null);
    }
}
