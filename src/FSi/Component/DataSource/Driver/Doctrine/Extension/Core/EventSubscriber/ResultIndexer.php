<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Doctrine\Extension\Core\EventSubscriber;

use FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\EventSubscriber\ResultIndexer as BaseIndexer;

/**
 * Class contains method called at BindParameters events.
 * @deprecated since version 1.2
 */
class ResultIndexer extends BaseIndexer
{
}
