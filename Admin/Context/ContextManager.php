<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\Element;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ContextManager
{
    /**
     * @var \FSi\Bundle\AdminBundle\Admin\Context\ContextInterface[]
     */
    protected $contexts;

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextInterface[] $contexts
     */
    public function __construct($contexts = array())
    {
        $this->contexts = array();

        foreach($contexts as $context) {
            $this->addContext($context);
        }
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Context\ContextInterface $builder
     */
    public function addContext(ContextInterface $builder)
    {
        $this->contexts[] = $builder;
    }

    /**
     * @param string $route
     * @param \FSi\Bundle\AdminBundle\Admin\Element $element
     * @return \FSi\Bundle\AdminBundle\Admin\Context\ContextInterface|null
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
