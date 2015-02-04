<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;

class MyResource extends GenericResourceElement
{
    public function getKey()
    {
        return 'resources.main_page';
    }

    public function getId()
    {
        return 'main_page';
    }

    public function getName()
    {
        return 'admin.main_page';
    }

    public function getRepository()
    {
    }

    /**
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resource
     */
    public function save(ResourceValue $resource)
    {
    }
}
