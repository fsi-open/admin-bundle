<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Fixtures;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FSi\Component\DataSource\Field\FieldAbstractExtension;
use FSi\Component\DataSource\Event\FieldEvents;

/**
 * Class to test DoctrineDriver extensions calls.
 */
class FieldExtension extends FieldAbstractExtension implements EventSubscriberInterface
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
            FieldEvents::PRE_BIND_PARAMETER => array('preBindParameter', 128),
            FieldEvents::POST_BIND_PARAMETER => array('postBindParameter', 128),
            FieldEvents::POST_BUILD_VIEW => array('postBuildView', 128),
            FieldEvents::POST_GET_PARAMETER => array('postGetParameter', 128),
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
