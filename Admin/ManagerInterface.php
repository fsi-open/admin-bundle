<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin;

interface ManagerInterface
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ElementInterface $element
     * @param string|null $group
     * @return \FSi\Bundle\AdminBundle\Admin\Manager
     */
    public function addElement(ElementInterface $element, $group = null);

    /**
     * @param string $id
     * @return bool
     */
    public function hasElement($id);

    /**
     * @param string $id
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface
     */
    public function getElement($id);

    /**
     * @param int $id
     */
    public function removeElement($id);

    /**
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface[]
     */
    public function getElements();

    /**
     * @return array
     */
    public function getGroups();

    /**
     * @param string $group
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface[]
     */
    public function getElementsByGroup($group);

    /**
     * @return \FSi\Bundle\AdminBundle\Admin\ElementInterface[]
     */
    public function getElementsWithoutGroup();
}