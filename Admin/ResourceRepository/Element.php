<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Admin\Element as BaseElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceInterface as ModelResourceInterface;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;

interface Element extends BaseElement
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
     * @param ModelResourceInterface $resource
     */
    public function save(ModelResourceInterface $resource);

    /**
     * @return ResourceValueRepository
     */
    public function getRepository();
}
