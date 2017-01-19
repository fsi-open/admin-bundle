<?php


namespace AdminPanel\Symfony\AdminBundle\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\Manager\Visitor;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class Manager implements ManagerInterface
{
    /**
     * @var \AdminPanel\Symfony\AdminBundle\Admin\Element[]
     */
    protected $elements;

    public function __construct()
    {
        $this->elements = array();
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @return \AdminPanel\Symfony\AdminBundle\Admin\Manager
     */
    public function addElement(Element $element)
    {
        $this->elements[$element->getId()] = $element;

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
     * @return \AdminPanel\Symfony\AdminBundle\Admin\Element
     */
    public function getElement($id)
    {
        return $this->elements[$id];
    }

    /**
     * @param int $id
     */
    public function removeElement($id)
    {
        unset($this->elements[$id]);
    }

    /**
     * @return \AdminPanel\Symfony\AdminBundle\Admin\Element[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param Visitor $visitor
     * @return mixed
     */
    public function accept(Visitor $visitor)
    {
        $visitor->visitManager($this);
    }
}
