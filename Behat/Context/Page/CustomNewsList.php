<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page;

class CustomNewsList extends Page
{
    protected $path = '/admin/custom_news/list';

    protected function verifyPage()
    {
        if (!$this->has('css', 'h1#page-header:contains("Custom list")')) {
            throw new BehaviorException(sprintf("%s page is missing \"Custom list\" header", $this->path));
        }
    }
}