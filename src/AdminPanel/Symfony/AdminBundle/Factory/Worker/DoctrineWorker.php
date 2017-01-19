<?php

namespace AdminPanel\Symfony\AdminBundle\Factory\Worker;

use Doctrine\Common\Persistence\ManagerRegistry;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\DoctrineAwareInterface;
use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\Element as DoctrineElement;
use AdminPanel\Symfony\AdminBundle\Factory\Worker;

class DoctrineWorker implements Worker
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @inheritdoc
     */
    public function mount(Element $element)
    {
        if ($element instanceof DoctrineAwareInterface || $element instanceof DoctrineElement) {
            $element->setManagerRegistry($this->managerRegistry);
        }
    }
}
