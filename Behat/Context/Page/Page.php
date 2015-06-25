<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Behat\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page as BasePage;

class Page extends BasePage
{
    public function getCollection($label)
    {
        return $this->find('xpath', sprintf('//div[@data-prototype]/ancestor::*[@class = "form-group"]/label[text() = "%s"]/..//div[@data-prototype]', $label));
    }
}
