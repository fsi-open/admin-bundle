<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;
use FSi\Component\DataIndexer\DataIndexerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

trait DependentElementImpl
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Element
     */
    private $parentElement;

    public function setParentElement(Element $element)
    {
        $this->parentElement = $element;
    }

    /**
     * @return Element
     */
    public function getParentElement()
    {
        return $this->parentElement;
    }

    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return object|null
     */
    public function getParentObject()
    {
        $dataIndexer = $this->getParentDataIndexer();
        $parentObjectId = $this->getParentObjectId();

        if ($dataIndexer !== null && $parentObjectId !== null) {
            return $dataIndexer->getData($parentObjectId);
        }

        return null;
    }

    /**
     * @return string
     */
    protected function getParentObjectId()
    {
        return $this->requestStack->getCurrentRequest()->get(DependentElement::REQUEST_PARENT_PARAMETER);
    }

    /**
     * @return DataIndexerInterface
     */
    protected function getParentDataIndexer()
    {
        if ($this->parentElement instanceof DataIndexerElement) {
            return $this->parentElement->getDataIndexer();
        }

        return null;
    }
}
