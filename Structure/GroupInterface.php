<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Structure;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
Interface GroupInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param $id
     * @return GroupInterface
     */
    public function setId($id);

    /**
     * @param ElementInterface $element
     * @return GroupInterface
     */
    public function addElement(ElementInterface $element);

    /**
     * @param array $elements
     * @return GroupInterface
     */
    public function setElements(array $elements = array());

    /**
     * @param $id
     * @return bool
     */
    public function hasElement($id);

    /**
     * @param $id
     * @return null
     */
    public function getElement($id);

    /**
     * @return array
     */
    public function getElements();
}