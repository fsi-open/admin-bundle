<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ElementVoter implements VoterInterface
{
    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(ManagerInterface $manager, RequestStack $requestStack)
    {
        $this->manager = $manager;
        $this->requestStack = $requestStack;
    }

    public function matchItem(ItemInterface $item)
    {
        if (false === $this->validateRequestElement()) {
            return null;
        }

        $element = $this->getRequestElement();

        while (true) {
            /** @var array $routes */
            $routes = $item->getExtra('routes', []);
            foreach ($routes as $testedRoute) {
                if (true === $this->isRouteMatchingElement($element, $testedRoute['parameters'])) {
                    return true;
                }
            }

            if (false === $element instanceof DependentElement) {
                break;
            }

            if (false === $this->manager->hasElement($element->getParentId())) {
                break;
            }

            $element = $this->manager->getElement($element->getParentId());
        }

        return false;
    }

    private function validateRequestElement(): bool
    {
        if (false === $this->getRequest()->attributes->has('element')) {
            return false;
        }

        $element = $this->getRequest()->attributes->get('element');
        if (false === $element instanceof Element) {
            return false;
        }

        return true;
    }

    private function getRequestElement(): Element
    {
        return $this->getRequest()->attributes->get('element');
    }

    private function isRouteMatchingElement(Element $element, array $testedRouteParameters): bool
    {
        return $this->isRouteMatchingElementDirectly($element, $testedRouteParameters)
            || $this->isRouteMatchingElementAfterSuccess($element, $testedRouteParameters)
        ;
    }

    private function isRouteMatchingElementDirectly(Element $element, array $testedRouteParameters): bool
    {
        if (!isset($testedRouteParameters['element'])) {
            return false;
        }

        return $testedRouteParameters['element'] === $element->getId();
    }

    private function isRouteMatchingElementAfterSuccess(Element $element, array $testedRouteParameters): bool
    {
        if (false === $element instanceof RedirectableElement) {
            return false;
        }

        if (!isset($testedRouteParameters['element'])) {
            return false;
        }

        $successParameters = $element->getSuccessRouteParameters();
        if (!isset($successParameters['element'])) {
            return false;
        }

        return $successParameters['element'] === $testedRouteParameters['element'];
    }

    private function getRequest(): Request
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new RuntimeException('No request!');
        }

        return $request;
    }
}
