<?php

namespace FSi\FixturesBundle\Admin\Structure;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\Bundle\AdminBundle\Annotation as Admin;

/**
 * @Admin\Element
 */
class HomePage extends ResourceElement
{
    public function getId()
    {
        return 'home_page';
    }

    public function getKey()
    {
        return 'resources.homepage';
    }

    public function getName()
    {
        return 'admin.home_page.name';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Resource';
    }
}
