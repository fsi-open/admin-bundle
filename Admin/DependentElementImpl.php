<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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

    public function setParentElement(Element $element): void
    {
        $this->parentElement = $element;
    }

    public function getParentElement(): Element
    {
        return $this->parentElement;
    }

    public function setRequestStack(RequestStack $requestStack): void
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

        if (null !== $dataIndexer && null !== $parentObjectId) {
            return $dataIndexer->getData($parentObjectId);
        }

        return null;
    }

    protected function getParentObjectId(): ?string
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if ($currentRequest === null) {
            return null;
        }

        return $currentRequest->get(DependentElement::PARENT_REQUEST_PARAMETER);
    }

    protected function getParentDataIndexer(): ?DataIndexerInterface
    {
        if (true === $this->parentElement instanceof DataIndexerElement) {
            return $this->parentElement->getDataIndexer();
        }

        return null;
    }
}
