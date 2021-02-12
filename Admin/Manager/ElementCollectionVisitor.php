<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Manager;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Factory\ProductionLine;

class ElementCollectionVisitor implements Visitor
{
    /**
     * @var Element[]
     */
    private $elements;

    /**
     * @var ProductionLine
     */
    private $factoryProductionLine;

    /**
     * @param Element[] $elements
     * @param ProductionLine $factoryProductionLine
     */
    public function __construct(array $elements, ProductionLine $factoryProductionLine)
    {
        $this->elements = $elements;
        $this->factoryProductionLine = $factoryProductionLine;
    }

    public function visitManager(ManagerInterface $manager): void
    {
        foreach ($this->elements as $element) {
            $this->factoryProductionLine->workOn($element);
            $manager->addElement($element);
        }
    }
}
