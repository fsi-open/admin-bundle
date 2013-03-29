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
class GroupManager implements GroupManagerInterface
{
    /**
     * @var array
     */
    protected $groups;

    /**
     * @param array $groups
     * @throws InvalidArgumentException
     */
    public function __construct($groups = array())
    {
        foreach ($groups as $group) {
            if (!$group instanceof GroupInterface) {
                throw new InvalidArgumentException("Group must implements GroupInterface.");
            }

            $this->addGroup($group);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addGroup(GroupInterface $group)
    {
        $this->groups[$group->getId()] = $group;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup($id)
    {
        if (!$this->hasGroup($id)) {
            return null;
        }

        return $this->groups[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function hasGroup($id)
    {
        return array_key_exists($id, $this->groups);
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param $id
     * @return null|GroupInterface
     */
    public function findElementById($id)
    {
        foreach ($this->groups as $group) {
            /* @var $group Group */
            if ($group->hasElement($id)) {
                return $group->getElement($id);
            }
        }

        return null;
    }
}