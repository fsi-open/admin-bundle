<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Context\Doctrine;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use FSi\Bundle\AdminBundle\Exception\InvalidEntityIdException;
use Symfony\Component\EventDispatcher\EventDispatcher;
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
     * @param EventDispatcher $dispatcher
     * @param \Symfony\Component\Routing\Router $router
     */
    public function __construct(EventDispatcher $dispatcher, Router $router)
    {
        $this->dispatcher = $dispatcher;
        $this->router = $router;
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
            if ($element->hasEditForm($this->getData($element))) {
                return true;
            }

            throw new ContextBuilderException(sprintf("%s does not have edit form", $element->getName()));
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function buildContext(ElementInterface $element)
    {
        /* @var $element \FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement */
        $context = new EditContext($this->dispatcher, $element, $this->router, $this->getData($element));

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
     * @throws \FSi\Bundle\AdminBundle\Exception\InvalidEntityIdException
     * @return mixed
     */
    protected function getData(ElementInterface $element)
    {
        $id = $this->request->get('id', null);
        $data = $element->getDataIndexer()->getData($id);

        if (!isset($data)) {
            throw new InvalidEntityIdException("Cant find entity with id 1");
        }

        return $data;
    }
}