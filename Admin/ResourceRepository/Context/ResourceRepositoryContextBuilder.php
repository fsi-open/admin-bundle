<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement;

class ResourceRepositoryContextBuilder implements ContextBuilderInterface
{
    /**
     * @var ResourceRepositoryContext
     */
    private $context;

    /**
     * @param ResourceRepositoryContext $context
     */
    public function __construct(ResourceRepositoryContext $context)
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

        if (!$element instanceof GenericResourceElement) {
            return false;
        }

        return true;
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
        return 'fsi_admin_resource';
    }
}
