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
use FSi\Bundle\AdminBundle\Request\Parameters;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_key_exists;

final class ElementVoter implements VoterInterface
{
    private ManagerInterface $manager;
    private RequestStack $requestStack;

    public function __construct(ManagerInterface $manager, RequestStack $requestStack)
    {
        $this->manager = $manager;
        $this->requestStack = $requestStack;
    }

    public function matchItem(ItemInterface $item): ?bool
    {
        $request = $this->getRequest();
        if (false === $this->validateRequestElement($request)) {
            return null;
        }

        $elementId = $request->attributes->get(Parameters::ELEMENT);
        if (false === is_string($elementId)) {
            return false;
        }

        $element = $this->manager->getElement($elementId);
        while (true) {
            /** @var array<int,mixed> $routes */
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

            $parentElement = $this->manager->getElement($element->getParentId());
            if ($parentElement === $element) {
                break;
            }

            $element = $parentElement;
        }

        return false;
    }

    private function validateRequestElement(Request $request): bool
    {
        if (false === $request->attributes->has(Parameters::ELEMENT)) {
            return false;
        }

        $elementId = $request->attributes->get(Parameters::ELEMENT);
        return $this->manager->hasElement($elementId);
    }

    /**
     * @param array<string,mixed> $testedRouteParameters
     */
    private function isRouteMatchingElement(Element $element, array $testedRouteParameters): bool
    {
        return true === $this->isRouteMatchingElementDirectly($element, $testedRouteParameters)
            || true === $this->isRouteMatchingElementAfterSuccess($element, $testedRouteParameters);
    }

    /**
     * @param array<string,mixed> $testedRouteParameters
     */
    private function isRouteMatchingElementDirectly(Element $element, array $testedRouteParameters): bool
    {
        if (false === array_key_exists(Parameters::ELEMENT, $testedRouteParameters)) {
            return false;
        }

        return $element->getId() === $testedRouteParameters[Parameters::ELEMENT];
    }

    /**
     * @param array<string,mixed> $testedRouteParameters
     */
    private function isRouteMatchingElementAfterSuccess(Element $element, array $testedRouteParameters): bool
    {
        if (false === $element instanceof RedirectableElement) {
            return false;
        }

        if (false === array_key_exists(Parameters::ELEMENT, $testedRouteParameters)) {
            return false;
        }

        $successParameters = $element->getSuccessRouteParameters();
        if (false === array_key_exists(Parameters::ELEMENT, $successParameters)) {
            return false;
        }

        return $successParameters[Parameters::ELEMENT] === $testedRouteParameters[Parameters::ELEMENT];
    }

    private function getRequest(): Request
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new RuntimeException('Menu is only available in request context');
        }

        return $request;
    }
}
