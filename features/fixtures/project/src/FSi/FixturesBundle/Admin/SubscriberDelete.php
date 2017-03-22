<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\DeleteElement;

class SubscriberDelete extends DeleteElement
{
    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Subscriber';
    }

    public function getId()
    {
        return 'subscriber_delete';
    }

    public function getSuccessRoute()
    {
        return 'fsi_admin_list';
    }

    public function getSuccessRouteParameters()
    {
        return ['element' => 'subscriber'];
    }
}
