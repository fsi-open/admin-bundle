<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;

class FormElementContextBuilder implements ContextBuilderInterface
{
    /**
     * @var FormElementContext
     */
    private $context;

    /**
     * @param FormElementContext $context
     */
    public function __construct(FormElementContext $context)
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

        if ($element instanceof FormElement) {
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
        return 'fsi_admin_form';
    }
}
