<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Fixtures;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FSi\Component\DataSource\DataSourceAbstractExtension;
use FSi\Component\DataSource\Event\DataSourceEvents;

/**
 * Class to test DataSource extensions calls.
 */
class DataSourceExtension extends DataSourceAbstractExtension implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $calls = array();

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            DataSourceEvents::PRE_BIND_PARAMETERS => array('preBindParameters', 128),
            DataSourceEvents::POST_BIND_PARAMETERS => array('postBindParameters', 128),
            DataSourceEvents::PRE_GET_RESULT => array('preGetResult', 128),
            DataSourceEvents::POST_GET_RESULT => array('postGetResult', 128),
            DataSourceEvents::PRE_BUILD_VIEW => array('preBuildView', 128),
            DataSourceEvents::POST_BUILD_VIEW => array('postBuildView', 128),
            DataSourceEvents::PRE_GET_PARAMETERS => array('preGetParameters', 128),
            DataSourceEvents::POST_GET_PARAMETERS => array('postGetParameters', 128),
        );
    }

    /**
     * Returns array of calls.
     *
     * @return array
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * Resets calls.
     */
    public function resetCalls()
    {
        $this->calls = array();
    }

    /**
     * Catches called method.
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        $this->calls[] = $name;
    }

    /**
     * Loads itself as subscriber.
     *
     * @return array
     */
    public function loadSubscribers()
    {
        return array($this);
    }
}
