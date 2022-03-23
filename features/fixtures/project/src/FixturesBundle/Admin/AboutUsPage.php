<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\FixturesBundle\Entity;

class AboutUsPage extends ResourceElement
{
    public function getId(): string
    {
        return 'about_us_page';
    }

    public function getKey(): string
    {
        return 'resources.about_us';
    }

    public function getClassName(): string
    {
        return Entity\Resource::class;
    }
}
