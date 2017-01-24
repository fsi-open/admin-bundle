<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Twig\Extension;

use FSi\Bundle\ResourceRepositoryBundle\Repository\Repository;

class ResourceRepository extends \Twig_Extension
{
    /**
     * @var \FSi\Bundle\ResourceRepositoryBundle\Repository\Repository
     */
    protected $repository;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'fsi_resource_repository';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('has_resource', [$this, 'hasResource']),
            new \Twig_SimpleFunction('get_resource', [$this, 'getResource'])
        ];
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasResource($key)
    {
        $resource = $this->repository->get($key);
        return isset($resource);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getResource($key, $default = null)
    {
        $value = $this->repository->get($key);
        return is_null($value) ? $default : $value;
    }
}
