<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;

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

    public function getClassName()
    {
    }
}
