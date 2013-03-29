<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Structure;

use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class GroupAbstract implements GroupInterface
{
    /**
     * Value of field is defined here to prevent using __constructor.
     *
     * @var array
     */
    private $elements = array();

    /**
     * {@inheritdoc}
     */
    public function addElement(ElementInterface $element)
    {
        if (array_key_exists($element->getId(), $this->elements)) {
            throw new InvalidArgumentException(sprintf('Element with id "%s" already exists in group.', $element->getId()));
        }

        $this->elements[$element->getId()] = $element;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setElements(array $elements = array())
    {
        foreach ($elements as $element) {
            $this->addElement($element);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasElement($id)
    {
        return array_key_exists($id, $this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function getElement($id)
    {
        if (!$this->hasElement($id)) {
            return null;
        }

        return $this->elements[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function getElements()
    {
        return $this->elements;
    }
}