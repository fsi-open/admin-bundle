<?php

namespace FSi\Bundle\AdminBundle\Factory;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;

class ProductionLine
{
    /**
     * @var Worker[]
     */
    protected $workers;

    /**
     * @param Worker[] $workers
     */
    public function __construct($workers = array())
    {
        $this->workers = array();

        foreach((array) $workers as $worker) {
            $this->addWorker($worker);
        }
    }

    /**
     * @param Worker $worker
     */
    public function addWorker(Worker $worker)
    {
        $this->workers[] = $worker;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->workers);
    }

    /**
     * @return array|Worker[]
     */
    public function getWorkers()
    {
        return $this->workers;
    }

    /**
     * Work on element with workers to make it ready to use.
     *
     * @param ElementInterface $element
     */
    public function workOn(ElementInterface $element)
    {
        foreach ($this->workers as $worker) {
            $worker->mount($element);
        }
    }
}
