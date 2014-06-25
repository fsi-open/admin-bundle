<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Create;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ContextBuilder implements ContextBuilderInterface
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
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

        if ($element instanceof CRUDElement) {
            if ($element->getOption('allow_add')) {
                return true;
            }

            throw new ContextBuilderException(sprintf("Element with id \"%s\" does not allow to create objects", $element->getId()));
        }

        return false;
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
        return 'fsi_admin_crud_create';
    }
}
