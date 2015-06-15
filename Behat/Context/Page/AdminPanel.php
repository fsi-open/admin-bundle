<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page;

class AdminPanel extends Page
{
    protected $path = '/admin/';

    public function hasMenuElement($name, $group = null)
    {
        if (!isset($group)) {
            return $this->getMenu()->has('css', sprintf('li > a:contains("%s")', $name));
        }

        $groupExpandButton = $this->getMenu()->find('css', sprintf('li.dropdown > a:contains("%s")', $group));

        if (isset($groupExpandButton)) {
            return $groupExpandButton->getParent()->has('css', sprintf('ul > li > a:contains("%s")', $name));
        }

        return false;
    }

    public function getMenuElementsCount()
    {
        return count($this->getMenu()->findAll('css', 'li.admin-element'));
    }

    public function getMenu()
    {
        return $this->find('css', '#top-menu');
    }

    public function getNavbarBrandText()
    {
        return $this->find('css', 'a.navbar-brand')->getText();
    }

    public function getLanguageDropdownOptions()
    {
        $link = $this->find('css', sprintf('li#language'));
        if (!isset($link)) {
            return null;
        }

        $linkNodes = $this->findAll('css', 'li#language > ul > li');

        return array_filter(array_map(function($element) {
            return $element->getText();
        }, $linkNodes));
    }

    public function getLanguageDropdown()
    {
        return $this->find('css', 'li#language');
    }
}