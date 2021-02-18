<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\Element;

class ContextManager
{
    /**
     * @var array<ContextInterface>
     */
    protected $contexts;

    /**
     * @param iterable<ContextInterface> $contexts
     */
    public function __construct(iterable $contexts)
    {
        $this->contexts = [];

        foreach ($contexts as $context) {
            $this->addContext($context);
        }
    }

    public function addContext(ContextInterface $builder): void
    {
        $this->contexts[] = $builder;
    }

    public function createContext(string $route, Element $element): ?ContextInterface
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
