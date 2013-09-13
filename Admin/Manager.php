<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class Manager
{
    /**
     * @var \FSi\Bundle\AdminBundle\Admin\ElementInterface[]
     */
    protected $elements;

    /**
     * @var array
     */
    protected $groups;

    public function __construct()
    {
        $this->elements = array();
        $this->groups = array();
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @param string|null $group
     * @return \FSi\Bundle\AdminBundle\Admin\Manager
     */
    public function addElement(ElementInterface $element, $group = null)
    {
        $this->elements[$element->getId()] = $element;
        if (isset($group)) {
            if (!array_key_exists($group, $this->groups)) {
                $this->groups[$group] = array();
            }

            $this->groups[$group][] = $element->getId();
        }

        return $this;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function hasElement($id)
    {
        return array_key_exists($id, $this->elements);
    }

    /**
     * @param string $id
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface
     */
    public function getElement($id)
    {
        return $this->elements[$id];
    }

    /**
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return array_keys($this->groups);
    }

    /**
     * @param $group
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface[]
     */
    public function getElementsByGroup($group)
    {
        $elements = array();
        foreach ($this->groups[$group] as $elementId) {
            $elements[$elementId] = $this->getElement($elementId);
        }

        return $elements;
    }

    /**
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface[]
     */
    public function getElementsWithoutGroup()
    {
        $elements = array();

        foreach ($this->elements as $id => $element) {
            if ($this->hasGroup($id)) {
                continue;
            }

            $elements[$id] = $element;
        }

        return $elements;
    }

    /**
     * Check if element is assigned into group.
     *
     * @param string $id
     * @return bool
     */
    private function hasGroup($id)
    {
        foreach ($this->groups as $group) {
            if (in_array($id, $group)) {
                return true;
            }
        }

        return false;
    }
}
