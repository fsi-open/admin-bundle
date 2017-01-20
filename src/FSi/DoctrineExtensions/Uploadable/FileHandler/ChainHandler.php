<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Uploadable\FileHandler;

use FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException;

class ChainHandler extends AbstractHandler
{
    /**
     * @var array
     */
    protected $handlers = array();

    /**
     * @param array $handlers
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     */
    public function __construct(array $handlers = array())
    {
        $i = 0;
        foreach ($handlers as $handler) {
            if (!$handler instanceof FileHandlerInterface) {
                throw new RuntimeException(sprintf(
                    'Handlers must be instances of FSi\\DoctrineExtensions\\Uploadable\\FileHandler\\FileHandlerInterface, "%s" given at position "%d"',
                    is_object($handler) ? get_class($handler) : gettype($handler),
                    $i
                ));
            }

            $this->handlers[] = $handler;
            $i++;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContent($file)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($file)) {
                return $handler->getContent($file);
            }
        }
        throw $this->generateNotSupportedException($file);
    }

    /**
     * {@inheritdoc}
     */
    public function getName($file)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($file)) {
                return $handler->getName($file);
            }
        }
        throw $this->generateNotSupportedException($file);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($file)) {
                return true;
            }
        }
        return false;
    }
}
