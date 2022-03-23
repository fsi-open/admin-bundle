<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

interface Element
{
    public function getClassName(): string;

    public function getObjectManager(): ObjectManager;

    public function getRepository(): ObjectRepository;

    public function setManagerRegistry(ManagerRegistry $registry): void;
}
