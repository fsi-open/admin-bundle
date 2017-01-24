<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\Manager;

use AdminPanel\Symfony\AdminBundle\Admin\ManagerInterface;

interface Visitor
{
    /**
     * @param ManagerInterface $manager
     */
    public function visitManager(ManagerInterface $manager);
}
