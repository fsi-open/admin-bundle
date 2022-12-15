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
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @template TParent of array<string,mixed>|object
 */
trait DependentElementImpl
{
    private RequestStack $requestStack;

    /**
     * @var DataIndexerElement<TParent>
     */
    private DataIndexerElement $parentElement;

    /**
     * @param Element&DataIndexerElement<TParent> $element
     */
    public function setParentElement(DataIndexerElement $element): void
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
     * @return TParent|null
     */
    public function getParentObject()
    {
        $dataIndexer = $this->parentElement->getDataIndexer();
        $parentObjectId = $this->getParentObjectId();

        if (null !== $parentObjectId) {
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
}
