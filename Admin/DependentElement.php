<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin;

interface DependentElement extends Element, RequestStackAware
{
    const REQUEST_PARENT_PARAMETER = 'parent';

    /**
     * ID of parent element
     *
     * @return string
     */
    public function getParentId();

    /**
     * @param Element $element
     */
    public function setParentElement(Element $element);

    /**
     * @return mixed
     */
    public function getParentObject();
}
