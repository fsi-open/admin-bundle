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

/**
 * @template T of object
 */
interface Element
{
    /**
     * @return class-string<T>
     */
    public function getClassName(): string;

    public function getObjectManager(): ObjectManager;

    /**
     * @return ObjectRepository<T>
     */
    public function getRepository(): ObjectRepository;

    public function setManagerRegistry(ManagerRegistry $registry): void;
}
