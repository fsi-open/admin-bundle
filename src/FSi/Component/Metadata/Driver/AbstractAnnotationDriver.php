<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use FSi\Component\Metadata\Driver\DriverInterface;

abstract class AbstractAnnotationDriver implements DriverInterface
{
    /**
     * Annotation reader is used to actually read the annotations
     *
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $reader;

    /**
     * Sets annotation reader for this driver
     *
     * @param Reader $reader
     * @return \FSi\Component\Metadata\Driver\AbstractAnnotationDriver
     */
    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
        return $this;
    }

    /**
     * Return previously set annotation reader or throws an exception
     *
     * @throws \RuntimeException
     * @return \Doctrine\Common\Annotations\Reader
     */
    public function getAnnotationReader()
    {
        if (!isset($this->reader)) {
            throw new \RuntimeException('Required annotation reader has not been set on the annotation driver.');
        }
        return $this->reader;
    }
}
