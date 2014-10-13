<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page;

use Behat\Behat\Exception\BehaviorException;

class CustomNewsEdit extends Page
{
    protected $path = '/admin/form/custom_news/{id}';

    protected function verifyPage()
    {
        if (!$this->has('css', 'h1#page-header:contains("Custom edit")')) {
            throw new BehaviorException(sprintf("%s page is missing \"Custom edit\" header", $this->path));
        }
    }
}