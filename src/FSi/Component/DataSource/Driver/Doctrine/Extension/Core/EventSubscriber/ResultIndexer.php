<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\Extension\Core\EventSubscriber;

use FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\EventSubscriber\ResultIndexer as BaseIndexer;

/**
 * Class contains method called at BindParameters events.
 * @deprecated since version 1.2
 */
class ResultIndexer extends BaseIndexer
{
}
