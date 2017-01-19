<?php


namespace AdminPanel\Symfony\AdminBundle\Menu\KnpMenu;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Request;

class ElementVoter implements VoterInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @inheritdoc
     */
    public function matchItem(ItemInterface $item)
    {
        if (!$this->validateRequestElement()) {
            return null;
        }

        foreach ($item->getExtra('routes', array()) as $testedRoute) {
            if ($this->isRouteMatchingElement($this->getRequestElement(), $testedRoute['parameters'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    private function validateRequestElement()
    {
        if (empty($this->request->attributes)) {
            return false;
        }

        if (!$this->request->attributes->has('element')) {
            return false;
        }

        $element = $this->request->attributes->get('element');
        if (!($element instanceof Element)) {
            return false;
        }

        return true;
    }

    /**
     * @return Element
     */
    private function getRequestElement()
    {
        return $this->request->attributes->get('element');
    }

    /**
     * @param Element $element
     * @param array $testedRouteParameters
     * @return bool
     */
    private function isRouteMatchingElement(Element $element, array $testedRouteParameters)
    {
        return $this->isRouteMatchingElementDirectly($element, $testedRouteParameters) ||
            $this->isRouteMatchingElementAfterSuccess($element, $testedRouteParameters);
    }

    /**
     * @param Element $element
     * @param array $testedRouteParameters
     * @return bool
     */
    private function isRouteMatchingElementDirectly(Element $element, array $testedRouteParameters)
    {
        if (!isset($testedRouteParameters['element'])) {
            return false;
        }

        return $testedRouteParameters['element'] === $element->getId();
    }

    /**
     * @param Element $element
     * @param array $testedRouteParameters
     * @return bool
     */
    private function isRouteMatchingElementAfterSuccess(Element $element, array $testedRouteParameters)
    {
        if (!($element instanceof RedirectableElement)) {
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
}
