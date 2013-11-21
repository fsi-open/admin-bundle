<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class ElementsListResults extends Element
{
    protected $selector = array('css' => 'div#list-results');

    public function setElementsPerPage($elementsCount)
    {
        return $this->find('css', 'ul[role="menu"]')->clickLink($elementsCount);
    }
}