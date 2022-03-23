<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Element;

use FriendsOfBehat\PageObjectExtension\Element\Element;

class ListResultsElement extends Element
{
    public function setElementsPerPage(int $elementsCount): void
    {
        $this->getDocument()->find('css', 'div#list-results ul[role="menu"]')->clickLink((string) $elementsCount);
    }
}
