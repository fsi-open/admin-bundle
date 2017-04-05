<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Display;

use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\DependentElementImpl;

abstract class DependentDisplayElement extends GenericDisplayElement implements DependentElement
{
    use DependentElementImpl;

    /**
     * {@inheritdoc}
     */
    public function getRouteParameters()
    {
        return array_merge(
            parent::getRouteParameters(),
            [DependentElement::REQUEST_PARENT_PARAMETER => $this->getParentObjectId()]
        );
    }
}
