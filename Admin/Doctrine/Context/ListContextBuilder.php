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
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ListContextBuilder implements ContextBuilderInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
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
        $context = new ListContext($this->dispatcher, $element);

        return $context;
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_crud_list';
    }
}