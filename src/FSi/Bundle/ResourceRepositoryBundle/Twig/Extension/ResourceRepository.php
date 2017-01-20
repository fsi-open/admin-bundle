<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        return array(
            new \Twig_SimpleFunction('has_resource', array($this, 'hasResource')),
            new \Twig_SimpleFunction('get_resource', array($this, 'getResource'))
        );
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
