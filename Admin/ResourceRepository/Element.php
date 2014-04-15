<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceInterface;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;

interface Element
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return array
     */
    public function getResourceFormOptions();

    /**
     * @param ResourceInterface $resource
     */
    public function save(ResourceInterface $resource);

    /**
     * @return ResourceValueRepository
     */
    public function getRepository();
}