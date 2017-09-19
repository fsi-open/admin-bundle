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

class Pagination extends Element
{
    protected $selector = ['css' => 'ul.pagination'];

    public function isDisabled(string $selector): bool
    {
        return $this->findLink($selector)->getParent()->hasClass('disabled');
    }

    public function isCurrentPage(string $selector): bool
    {
        return $this->findLink($selector)->getParent()->hasClass('active');
    }
}
