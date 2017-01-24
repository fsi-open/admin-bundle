<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Element;

class ContextManager
{
    /**
     * @var \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface[]
     */
    protected $contexts;

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface[] $contexts
     */
    public function __construct($contexts = [])
    {
        $this->contexts = [];

        foreach ($contexts as $context) {
            $this->addContext($context);
        }
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface $builder
     */
    public function addContext(ContextInterface $builder)
    {
        $this->contexts[] = $builder;
    }

    /**
     * @param string $route
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @return \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface|null
     */
    public function createContext($route, Element $element)
    {
        foreach ($this->contexts as $context) {
            if ($context->supports($route, $element)) {
                $context->setElement($element);
                return $context;
            }
        }

        return null;
    }
}
