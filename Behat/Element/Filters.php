<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Element;

use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Element\Element;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;

use function sprintf;

class Filters extends Element
{
    public function hasFilter(string $filterName): bool
    {
        return $this->getFiltersElement()->hasField($filterName);
    }

    public function hasBetweenFilter(string $filterName, string $fromName, string $toName): bool
    {
        $filters = $this->getFiltersElement();

        return null !== $filters->find('css', sprintf('label:contains("%s")', $filterName))
            && null !== $filters->findField($fromName)
            && null !== $filters->findField($toName);
    }

    public function hasChoiceFilter(string $filterName): bool
    {
        $filters = $this->getFiltersElement();

        return true === $filters->hasField($filterName)
            && 'select' === $this->getFilterElement($filterName)->getTagName()
        ;
    }

    public function setFilterValue(string $filterName, string $value): void
    {
        $this->getFilterElement($filterName)->setValue($value);
    }

    public function setFilterOption(string $filterName, string $value): void
    {
        $this->getFilterElement($filterName)->selectOption($value);
    }

    public function getFilerValue(string $filterName): string
    {
        return $this->getFilterElement($filterName)->getValue();
    }

    public function getFilterOption(string $filterName): string
    {
        return $this->getFilterElement($filterName)->find('css', sprintf('option[selected]'))->getText();
    }

    public function submitFilters(): void
    {
        $this->getFiltersElement()->pressButton('Search');
    }

    private function getFiltersElement(): NodeElement
    {
        $nodeElement = $this->getDocument()->find('css', 'form.filters');
        if (false === $nodeElement instanceof NodeElement) {
            throw new UnexpectedPageException("Page does not contain filters");
        }

        return $nodeElement;
    }

    private function getFilterElement(string $filterName): NodeElement
    {
        $nodeElement = $this->getFiltersElement()->findField($filterName);
        if (false === $nodeElement instanceof NodeElement) {
            throw new UnexpectedPageException("Filters does not contain filter {$filterName}");
        }

        return $nodeElement;
    }
}
