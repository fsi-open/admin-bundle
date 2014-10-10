<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page;

use Behat\Behat\Exception\BehaviorException;

class NewsCreate extends Page
{
    protected $path = '/admin/news/create';

    public function getHeader()
    {
        return $this->find('css', 'h3#page-header')->getText();
    }

    protected function verifyPage()
    {
        if (!$this->has('css', 'h3#page-header:contains("New element")')) {
            throw new BehaviorException(sprintf("%s page is missing \"New element\" header", $this->path));
        }
    }
}
