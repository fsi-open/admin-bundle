<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use Doctrine\Persistence\ManagerRegistry;
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

    public function mount(Element $element): void
    {
        if (true === $element instanceof DoctrineElement) {
            $element->setManagerRegistry($this->managerRegistry);
        }
    }
}
