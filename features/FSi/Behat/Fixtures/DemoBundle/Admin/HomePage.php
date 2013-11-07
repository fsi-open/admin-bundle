<?php

namespace FSi\Behat\Fixtures\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement;

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
    }
}