<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Behat\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class AdminPanel extends Page
{
    protected $path = '/admin';

    public function hasMenuElement($name, $group = null)
    {
        $menu = $this->find('css', '#top-menu');

        if (!isset($group)) {
            return $menu->has('css', sprintf('li > a:contains("%s")', $name));
        }

        $groupExpandButton = $this->find('css', sprintf('li.dropdown > a:contains("%s")', $group));

        if (isset($groupExpandButton)) {
            return $groupExpandButton->getParent()->has('css', sprintf('ul > li > a:contains("%s")', $name));
        }

        return false;
    }

    public function getNavbarBrandText()
    {
        return $this->find('css', 'a.navbar-brand')->getText();
    }
}