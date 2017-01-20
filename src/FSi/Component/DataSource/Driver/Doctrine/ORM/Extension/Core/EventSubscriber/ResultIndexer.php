<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\EventSubscriber;

use Doctrine\ORM\Tools\Pagination\Paginator;
use FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineResult;
use FSi\Component\DataSource\Event\DriverEvent\ResultEventArgs;
use FSi\Component\DataSource\Event\DriverEvents;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class contains method called at BindParameters events.
 */
class ResultIndexer implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Bridge\Doctrine\ManagerRegistry
     */
    protected $registry;

    /**
     * @param \Symfony\Bridge\Doctrine\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(DriverEvents::POST_GET_RESULT => array('postGetResult', 1024));
    }

    /**
     * @param \FSi\Component\DataSource\Event\DriverEvent\ResultEventArgs $event
     */
    public function postGetResult(ResultEventArgs $event)
    {
        $result = $event->getResult();

        if ($result instanceof Paginator) {
            $result = new DoctrineResult($this->registry, $result);
            $event->setResult($result);
        }
    }
}
