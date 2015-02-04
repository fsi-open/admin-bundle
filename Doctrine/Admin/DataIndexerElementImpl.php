<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use FSi\Component\DataIndexer\DoctrineDataIndexer;

trait DataIndexerElementImpl
{
    use ElementImpl;

    /**
     * @return \FSi\Component\DataIndexer\DoctrineDataIndexer
     */
    public function getDataIndexer()
    {
        return new DoctrineDataIndexer($this->registry, $this->getRepository()->getClassName());
    }
}
