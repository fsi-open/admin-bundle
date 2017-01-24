<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository;

use AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;

interface Element extends RedirectableElement
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return array
     */
    public function getResourceFormOptions();

    /**
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resource
     */
    public function save(ResourceValue $resource);

    /**
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository
     */
    public function getRepository();
}
