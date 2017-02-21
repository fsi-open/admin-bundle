<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

class HomePageEdit extends Page
{
    protected $path = '/admin/resource/home_page';

    public function getHeader()
    {
        return $this->find('css', '#page-header')->getText();
    }

    protected function verifyPage()
    {
        if (!$this->has('css', '#page-header:contains("Edit resources")')) {
            throw new UnexpectedPageException(sprintf("%s page is missing \"Resource edit\" header", $this->path));
        }
    }
}
