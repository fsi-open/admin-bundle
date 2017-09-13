<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use FSi\Component\DataIndexer\DataIndexerInterface;
use FSi\Component\DataIndexer\DoctrineDataIndexer;

trait DataIndexerElementImpl
{
    use ElementImpl;

    public function getDataIndexer(): DataIndexerInterface
    {
        return new DoctrineDataIndexer($this->registry, $this->getRepository()->getClassName());
    }
}
