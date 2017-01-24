<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\Manager\Visitor;

interface ManagerInterface
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @return \AdminPanel\Symfony\AdminBundle\Admin\Manager
     */
    public function addElement(Element $element);

    /**
     * @param string $id
     * @return bool
     */
    public function hasElement($id);

    /**
     * @param string $id
     * @return \AdminPanel\Symfony\AdminBundle\Admin\Element
     */
    public function getElement($id);

    /**
     * @param int $id
     */
    public function removeElement($id);

    /**
     * @return \AdminPanel\Symfony\AdminBundle\Admin\Element[]
     */
    public function getElements();

    /**
     * @param Visitor $visitor
     * @return mixed
     */
    public function accept(Visitor $visitor);
}
