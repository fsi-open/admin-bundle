<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\ResourceRepositoryBundle\Model;

interface ResourceValueRepository
{
    /**
     * @param $key
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue
     */
    public function get($key);

    /**
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resourceValue
     */
    public function add(ResourceValue $resourceValue);


    /**
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resourceValue
     */
    public function save(ResourceValue $resourceValue);

    /**
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resourceValue
     */
    public function remove(ResourceValue $resourceValue);
}
