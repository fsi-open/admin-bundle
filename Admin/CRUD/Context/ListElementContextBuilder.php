<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;

class ListElementContextBuilder implements ContextBuilderInterface
{
    /**
     * @var ListElementContext
     */
    private $context;

    /**
     * @param ListElementContext $context
     */
    public function __construct(ListElementContext $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($route, Element $element)
    {
        if ($route !== $this->getSupportedRoute()) {
            return false;
        }

        if ($element instanceof ListElement) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function buildContext(Element $element)
    {
        $this->context->setElement($element);

        return $this->context;
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_list';
    }
}
