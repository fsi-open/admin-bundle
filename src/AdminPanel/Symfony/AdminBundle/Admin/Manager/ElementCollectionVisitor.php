<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\Manager;

use AdminPanel\Symfony\AdminBundle\Admin\Manager;
use AdminPanel\Symfony\AdminBundle\Admin\ManagerInterface;
use AdminPanel\Symfony\AdminBundle\Factory\ProductionLine;

class ElementCollectionVisitor implements Visitor
{
    /**
     * @var array
     */
    private $elements;

    /**
     * @var \AdminPanel\Symfony\AdminBundle\Factory\ProductionLine
     */
    private $factoryProductionLine;

    public function __construct($elements = array(), ProductionLine $factoryProductionLine)
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
