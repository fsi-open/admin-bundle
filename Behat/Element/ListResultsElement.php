<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class ListResultsElement extends Element
{
    protected $selector = ['css' => 'div#list-results'];

    public function setElementsPerPage(int $elementsCount): void
    {
        $this->find('css', 'ul[role="menu"]')->clickLink($elementsCount);
    }
}
