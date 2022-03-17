<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Behat\Element;

use FriendsOfBehat\PageObjectExtension\Element\Element;

class Display extends Element
{
    public function hasFieldWithName($fieldName): bool
    {
        $selector = sprintf('table.table.table-bordered tr td:first-child:contains("%s")', $fieldName);

        return $this->getDocument()->find('css', $selector) !== null;
    }
}
