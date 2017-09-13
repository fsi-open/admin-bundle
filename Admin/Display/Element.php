<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Display;

use FSi\Bundle\AdminBundle\Admin\Element as BaseElement;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Component\DataIndexer\DataIndexerInterface;

interface Element extends BaseElement
{
    /**
     * @param mixed $data
     * @return Display
     */
    public function createDisplay($data): Display;

    public function getDataIndexer(): DataIndexerInterface;
}
