<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Item;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;

class ElementItem extends RoutableItem
{
    /**
     * @var ElementInterface
     */
    private $element;

    function __construct($name, ElementInterface $element)
    {
        parent::__construct($name);

        $this->element = $element;
    }

    /**
     * @return ElementInterface
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @return bool
     */
    public function hasElement()
    {
        return isset($this->element);
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->element->getRoute();
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->element->getRouteParameters();
    }
}
