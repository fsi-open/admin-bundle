<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\CRUDElement;
use FSi\Bundle\AdminBundle\Admin\Element;

class CRUDFormElementContextBuilder extends FormElementContextBuilder
{
    /**
     * @param CRUDFormElementContext $context
     */
    public function __construct(CRUDFormElementContext $context)
    {
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($route, Element $element)
    {
        if (!parent::supports($route, $element)) {
            return false;
        }

        return $element instanceof CRUDElement;
    }
}
