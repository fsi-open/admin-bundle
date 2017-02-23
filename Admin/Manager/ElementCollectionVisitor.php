<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Manager;

use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Factory\ProductionLine;

class ElementCollectionVisitor implements Visitor
{
    /**
     * @var array
     */
    private $elements;

    /**
     * @var \FSi\Bundle\AdminBundle\Factory\ProductionLine
     */
    private $factoryProductionLine;

    public function __construct($elements = [], ProductionLine $factoryProductionLine)
    {
        $this->elements = $elements;
        $this->factoryProductionLine = $factoryProductionLine;
    }

    /**
     * @param ManagerInterface $manager
     */
    public function visitManager(ManagerInterface $manager)
    {
        foreach ($this->elements as $element) {
            $this->factoryProductionLine->workOn($element);
            $manager->addElement($element);
        }
    }
}
