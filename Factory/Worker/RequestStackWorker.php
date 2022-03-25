<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\RequestStackAware;
use FSi\Bundle\AdminBundle\Factory\Worker;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStackWorker implements Worker
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function mount(Element $element): void
    {
        if (true === $element instanceof RequestStackAware) {
            $element->setRequestStack($this->requestStack);
        }
    }
}
