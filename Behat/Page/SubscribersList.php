<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

class SubscribersList extends Page
{
    protected $path = '/admin/list/subscriber';

    protected function verifyPage(): void
    {
        if (false === $this->has('css', '#page-header:contains("List of elements")')) {
            throw new UnexpectedPageException(sprintf('%s page is missing "List of elements" header', $this->path));
        }
    }
}
