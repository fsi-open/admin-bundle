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
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\Router;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CreateContextBuilder implements ContextBuilderInterface
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
     * @param EventDispatcher $dispatcher
     * @param \Symfony\Component\Routing\Router $router
     */
    public function __construct(EventDispatcher $dispatcher, Router $router)
    {
        $this->dispatcher = $dispatcher;
        $this->router = $router;
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
            if ($element->hasCreateForm()) {
                return true;
            }

            throw new ContextBuilderException(sprintf("%s does not have create form", $element->getName()));
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function buildContext(ElementInterface $element)
    {
        /* @var $element \FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement */
        $context = new CreateContext($this->dispatcher, $element, $this->router);

        return $context;
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_crud_create';
    }
}