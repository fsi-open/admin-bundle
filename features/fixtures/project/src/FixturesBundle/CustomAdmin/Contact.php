<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\CustomAdmin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\FixturesBundle\Entity;

class Contact extends ResourceElement
{
    public function getClassName(): string
    {
        return Entity\Resource::class;
    }

    public function getId(): string
    {
        return 'contact';
    }

    public function getKey(): string
    {
        return 'contact';
    }
}
