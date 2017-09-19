<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;

abstract class ResourceElement extends GenericResourceElement implements Element
{
    use ElementImpl;

    public function getResourceValueRepository(): ResourceValueRepository
    {
        return $this->getRepository();
    }

    public function save(ResourceValue $resource): void
    {
        $this->getObjectManager()->persist($resource);
        $this->getObjectManager()->flush();
    }
}
