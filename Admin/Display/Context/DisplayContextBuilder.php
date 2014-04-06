<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Display\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;
use FSi\Bundle\AdminBundle\Admin\Display\GenericDisplayElement;
use FSi\Bundle\AdminBundle\Admin\ElementInterface;

class DisplayContextBuilder implements ContextBuilderInterface
{
    /**
     * @var DisplayContext
     */
    private $context;

    /**
     * @param DisplayContext $context
     */
    public function __construct(DisplayContext $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($route, ElementInterface $element)
    {
        if ($route !== $this->getSupportedRoute()) {
            return false;
        }

        if (!$element instanceof GenericDisplayElement) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function buildContext(ElementInterface $element)
    {
        $this->context->setElement($element);

        return $this->context;
    }

    /**
     * @return string
     */
    protected function getSupportedRoute()
    {
        return 'fsi_admin_display';
    }
}
