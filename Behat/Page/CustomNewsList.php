<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

class CustomNewsList extends Page
{
    protected $path = '/admin/list/custom_news';

    protected function verifyPage()
    {
        if (!$this->has('css', 'h1#page-header:contains("Custom list")')) {
            throw new UnexpectedPageException(sprintf("%s page is missing \"Custom list\" header", $this->path));
        }
    }
}
