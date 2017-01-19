<?php


namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles;

use AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;

class MyResource extends GenericResourceElement
{
    public function getKey()
    {
        return 'resources.main_page';
    }

    public function getId()
    {
        return 'main_page';
    }

    public function getName()
    {
        return 'admin.main_page';
    }

    public function getRepository()
    {
    }

    /**
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resource
     */
    public function save(ResourceValue $resource)
    {
    }
}
