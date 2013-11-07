<?php

namespace FSi\Behat\Fixtures\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement;

class AboutUsPage extends ResourceElement
{
    public function getId()
    {
        return 'about_us_page';
    }

    public function getKey()
    {
        return 'resources.about_us';
    }

    public function getName()
    {
        return 'admin.about_us_page.name';
    }

    public function getClassName()
    {
    }
}