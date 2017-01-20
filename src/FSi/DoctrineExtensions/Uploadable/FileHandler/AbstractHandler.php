<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Uploadable\FileHandler;

use FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException;

abstract class AbstractHandler implements FileHandlerInterface
{
    /**
     * @param $file
     * @return \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     */
    protected function generateNotSupportedException($file)
    {
        return new RuntimeException(sprintf('Resource "%s" not supported.', is_object($file) ? get_class($file) : gettype($file)));
    }
}
