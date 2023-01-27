<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Page;

use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;

class CustomSubscribersList extends Page
{
    public function verify(array $urlParameters = []): void
    {
        parent::verify($urlParameters);

        if (false === $this->getDocument()->has('css', 'h1#page-header:contains("Custom subscribers list")')) {
            throw new UnexpectedPageException(
                sprintf('%s page is missing "Custom subscribers list" header', $this->getUrl($urlParameters))
            );
        }
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return $this->getParameter('base_url') . '/admin/en/list/custom_subscriber';
    }
}
