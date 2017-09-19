<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;

class MyResourceElement extends ResourceElement
{
    public function getId(): string
    {
        return 'main_page';
    }

    public function getKey(): string
    {
        return 'resources.main_page';
    }

    public function getClassName(): string
    {
        return 'FSi\Bundle\DemoBundle\Entity\Resource';
    }
}
