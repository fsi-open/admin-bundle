<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

class PersonEditForm extends Page
{
    protected $path = '/admin/form/person/{id}';

    public function getHeader()
    {
        return $this->find('css', 'h3#page-header')->getText();
    }

    protected function verifyPage()
    {
        if (!$this->has('css', '#page-header:contains("Edit element")')) {
            throw new UnexpectedPageException(sprintf("%s page is missing \"Edit element\" header", $this->path));
        }
    }
}
