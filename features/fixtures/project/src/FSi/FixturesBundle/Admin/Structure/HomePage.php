<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin\Structure;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\FixturesBundle\Entity;

class HomePage extends ResourceElement
{
    public function getId(): string
    {
        return 'home_page';
    }

    public function getKey(): string
    {
        return 'resources.homepage';
    }

    public function getClassName(): string
    {
        return Entity\Resource::class;
    }
}
