<?php

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\RequestStackAware;
use FSi\Bundle\AdminBundle\Factory\Worker;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStackWorker implements Worker
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @inheritdoc
     */
    public function mount(Element $element)
    {
        if ($element instanceof RequestStackAware) {
            $element->setRequestStack($this->requestStack);
        }
    }
}
