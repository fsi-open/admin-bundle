<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page\Element;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class Pagination extends Element
{
    protected $selector = ['css' => 'ul.pagination'];

    public function isDisabled($selector)
    {
        return $this->findLink($selector)->getParent()->hasClass('disabled');
    }

    public function isCurrentPage($selector)
    {
        return $this->findLink($selector)->getParent()->hasClass('active');
    }
}
