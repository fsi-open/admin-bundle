<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericDeleteElement;

/**
 * @template T of object
 * @template-extends GenericDeleteElement<T>
 * @template-implements Element<T>
 */
abstract class DeleteElement extends GenericDeleteElement implements Element
{
    /** @use DataIndexerElementImpl<T> */
    use DataIndexerElementImpl;

    public function delete($object): void
    {
        $this->getObjectManager()->remove($object);
        $this->getObjectManager()->flush();
    }
}
