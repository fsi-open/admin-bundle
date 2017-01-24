<?php

declare(strict_types=1);

namespace FSi\Component\Metadata\Driver;

use FSi\Component\Metadata\ClassMetadataInterface;

interface DriverInterface
{
    /**
     * Load metadata into object.
     *
     * @param ClassMetadataInterface $metadata
     */
    public function loadClassMetadata(ClassMetadataInterface $metadata);
}
