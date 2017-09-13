<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\DeleteElement;
use FSi\FixturesBundle\Entity;

class SubscriberDelete extends DeleteElement
{
    public function getClassName(): string
    {
        return Entity\Subscriber::class;
    }

    public function getId(): string
    {
        return 'subscriber_delete';
    }

    public function getSuccessRoute(): string
    {
        return 'fsi_admin_list';
    }

    public function getSuccessRouteParameters(): array
    {
        return ['element' => 'subscriber'];
    }
}
