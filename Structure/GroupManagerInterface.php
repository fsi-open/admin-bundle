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
interface GroupManagerInterface
{
    /**
     * @param GroupInterface $group
     * @return $this
     */
    public function addGroup(GroupInterface $group);

    /**
     * @param string $id
     * @return null|ElementInterface
     */
    public function getGroup($id);

    /**
     * @param string $id
     * @return bool
     */
    public function hasGroup($id);

    /**
     * @return array
     */
    public function getGroups();
}