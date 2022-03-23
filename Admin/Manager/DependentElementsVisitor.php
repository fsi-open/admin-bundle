<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Manager;

use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;

class DependentElementsVisitor implements Visitor
{
    public static function getPriority(): int
    {
        return 0;
    }

    public function visitManager(ManagerInterface $manager): void
    {
        foreach ($manager->getElements() as $element) {
            if (false === $element instanceof DependentElement) {
                continue;
            }

            $element->setParentElement($manager->getElement($element->getParentId()));
        }
    }
}
