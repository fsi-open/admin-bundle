<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page;

use Behat\Behat\Exception\BehaviorException;

class NewsDeleteConfirmation extends Page
{
    public function getConfirmationMessage()
    {
        return $this->find('css', 'div#delete-wrapper > div > p')->getText();
    }

    protected function verifyPage()
    {
        if (!$this->has('css', 'div#delete-wrapper')) {
            throw new BehaviorException(sprintf("Page is not a delete confirmation page"));
        }
    }
}
