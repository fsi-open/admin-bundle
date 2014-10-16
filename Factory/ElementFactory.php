<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Factory;

use FSi\Bundle\AdminBundle\Admin\Element;

class ElementFactory
{
    /**
     * @var ProductionLine
     */
    private $productionLine;

    /**
     * @param ProductionLine $productionLine
     */
    public function __construct(ProductionLine $productionLine)
    {
        $this->productionLine = $productionLine;
    }
    /**
     * @param string $class
     * @return \FSi\Bundle\AdminBundle\Admin\Element
     * @throws \InvalidArgumentException
     */
    public function create($class)
    {
        $element = new $class;
        if (!$element instanceof Element) {
            throw new \InvalidArgumentException(sprintf("%s does not seems to be an admin element.", $class));
        }

        $this->productionLine->workOn($element);

        return $element;
    }
}
