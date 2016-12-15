<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Manager;

use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;

class DependentElementsVisitor implements Visitor
{
    /**
     * @param ManagerInterface $manager
     */
    public function visitManager(ManagerInterface $manager)
    {
        foreach ($manager->getElements() as $element) {
            if (!($element instanceof DependentElement)) {
                continue;
            }

            $element->setParentElement($manager->getElement($element->getParentId()));
        }
    }
}
