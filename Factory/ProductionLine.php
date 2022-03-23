<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Factory;

use FSi\Bundle\AdminBundle\Admin\Element;

class ProductionLine
{
    /**
     * @var array<Worker>
     */
    protected $workers;

    /**
     * @param iterable<Worker> $workers
     */
    public function __construct(iterable $workers)
    {
        $this->workers = [];

        foreach ($workers as $worker) {
            $this->addWorker($worker);
        }
    }

    public function addWorker(Worker $worker): void
    {
        $this->workers[] = $worker;
    }

    public function count(): int
    {
        return count($this->workers);
    }

    /**
     * @return array<Worker>
     */
    public function getWorkers(): array
    {
        return $this->workers;
    }

    public function workOn(Element $element): void
    {
        foreach ($this->workers as $worker) {
            $worker->mount($element);
        }
    }
}
