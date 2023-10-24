<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Page;

use Behat\Mink\Element\NodeElement;

class AdminPanel extends Page
{
    public function getHtml(): string
    {
        return $this->getDocument()->getHtml();
    }

    public function hasMenuElement(string $name, ?string $group = null): bool
    {
        if (null === $group) {
            return $this->getMenu()->has('css', "li > a:contains(\"{$name}\")");
        }

        $groupExpandButton = $this->getMenu()->find(
            'css',
            "li.dropdown > a:contains(\"{$group}\")"
        );

        if (null === $groupExpandButton) {
            return false;
        }

        return $groupExpandButton->getParent()->has(
            'css',
            "ul > li > a:contains(\"{$name}\")"
        );
    }

    public function getMenuElementsCount(): int
    {
        return count($this->getMenu()->findAll('css', 'li.admin-element'));
    }

    public function getMenu(): ?NodeElement
    {
        return $this->getDocument()->find('css', '#top-menu');
    }

    public function getNavbarBrandText(): string
    {
        return $this->getDocument()->find('css', 'a.navbar-brand')->getText();
    }

    public function getLanguageDropdownOptions(): array
    {
        $link = $this->getLanguageDropdown();
        if (null === $link) {
            return [];
        }

        $linkNodes = $this->getDocument()->findAll('css', 'li#language > ul > li');
        return array_filter(
            array_map(
                static fn(NodeElement $element): ?string => $element->getText(),
                $linkNodes
            )
        );
    }

    public function getLanguageDropdown(): ?NodeElement
    {
        return $this->getDocument()->find('css', 'li#language');
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return $this->getParameter('base_url') . '/admin/en';
    }
}
