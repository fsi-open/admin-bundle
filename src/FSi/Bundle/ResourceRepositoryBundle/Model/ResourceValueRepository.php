<?php

declare(strict_types=1);

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
