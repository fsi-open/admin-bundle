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
    protected $path = '/admin/';

    public function hasMenuElement(string $name, ?string $group = null): bool
    {
        if (null === $group) {
            return $this->getMenu()->has('css', sprintf('li > a:contains("%s")', $name));
        }

        $groupExpandButton = $this->getMenu()->find('css', sprintf('li.dropdown > a:contains("%s")', $group));

        if (null !== $groupExpandButton) {
            return $groupExpandButton->getParent()->has('css', sprintf('ul > li > a:contains("%s")', $name));
        }

        return false;
    }

    public function getMenuElementsCount(): int
    {
        return count($this->getMenu()->findAll('css', 'li.admin-element'));
    }

    public function getMenu(): ?NodeElement
    {
        return $this->find('css', '#top-menu');
    }

    public function getNavbarBrandText(): string
    {
        return $this->find('css', 'a.navbar-brand')->getText();
    }

    public function getLanguageDropdownOptions(): array
    {
        $link = $this->getLanguageDropdown();
        if (null === $link) {
            return [];
        }

        $linkNodes = $this->findAll('css', 'li#language > ul > li');

        return array_filter(array_map(function(NodeElement $element) {
            return $element->getText();
        }, $linkNodes));
    }

    public function getLanguageDropdown(): ?NodeElement
    {
        return $this->find('css', 'li#language');
    }
}
