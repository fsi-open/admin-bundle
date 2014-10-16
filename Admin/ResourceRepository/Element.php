<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Admin\Element as BaseElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;

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
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resource
     */
    public function save(ResourceValue $resource);

    /**
     * @return \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository
     */
    public function getRepository();
}
