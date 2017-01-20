<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Extension\Symfony\Core;

use FSi\Component\DataSource\DataSourceAbstractExtension;

/**
 * Main extension for all Symfony based extensions. Its main purpose is to
 * replace binded Request object into array.
 */
class CoreExtension extends DataSourceAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return array(
            new EventSubscriber\BindParameters(),
        );
    }
}
