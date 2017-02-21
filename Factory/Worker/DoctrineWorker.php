<?php

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Element as DoctrineElement;
use FSi\Bundle\AdminBundle\Factory\Worker;

class DoctrineWorker implements Worker
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @inheritdoc
     */
    public function mount(Element $element)
    {
        if ($element instanceof DoctrineElement) {
            $element->setManagerRegistry($this->managerRegistry);
        }
    }
}
