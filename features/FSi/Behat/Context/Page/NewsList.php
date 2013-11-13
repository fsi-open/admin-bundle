<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Behat\Context\Page;

use Behat\Behat\Exception\BehaviorException;

class NewsList extends Page
{
    protected $path = '/admin/news/list';

    protected function verifyPage()
    {
        if (!$this->has('css', 'h3:contains("List of elements")')) {
            throw new BehaviorException(sprintf("%s page is missing \"List of elements\" header", $this->path));
        }
    }
}