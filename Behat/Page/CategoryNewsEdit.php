<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;

class CategoryNewsEdit extends Page
{
    protected $path = '/admin/form/category_news/{id}?parent={parent_id}';
    protected $elements = [
        'page header' => '#page-header',
    ];

    public function getHeader()
    {
        if (!$this->hasElement('page header')) {
            throw new \Exception('Unable to find page header');
        }

        return $this->getElement('page header')->getText();
    }

    protected function verifyPage()
    {
        if (!$this->has('css', '#page-header:contains("Edit element")')) {
            throw new UnexpectedPageException(sprintf("%s page is missing \"New element\" header", $this->path));
        }
    }
}
