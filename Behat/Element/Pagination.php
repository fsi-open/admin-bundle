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

class Pagination extends Element
{
    public function hasLink(string $selector): bool
    {
        return $this->getPagination()->hasLink($selector);
    }

    public function clickLink(string $selector): void
    {
        $this->getPagination()->clickLink($selector);
    }

    public function isDisabled(string $selector): bool
    {
        return $this->getPagination()->findLink($selector)->getParent()->hasClass('disabled');
    }

    public function isCurrentPage(string $selector): bool
    {
        return $this->getPagination()->findLink($selector)->getParent()->hasClass('active');
    }

    private function getPagination(): NodeElement
    {
        return $this->getDocument()->find('css', 'ul.pagination');
    }
}
