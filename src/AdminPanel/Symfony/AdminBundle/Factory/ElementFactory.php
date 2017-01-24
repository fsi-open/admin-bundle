<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Factory;

use AdminPanel\Symfony\AdminBundle\Admin\Element;

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
     * @return \AdminPanel\Symfony\AdminBundle\Admin\Element
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
