<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericCRUDElement;

/**
 * @template T of object
 * @template-implements Element<T>
 */
abstract class CRUDElement extends GenericCRUDElement implements Element
{
    /** @use DataIndexerElementImpl<T> */
    use DataIndexerElementImpl;

    public function save($object): void
    {
        $this->getObjectManager()->persist($object);
        $this->getObjectManager()->flush();
    }

    public function saveDataGrid(): void
    {
        $this->getObjectManager()->flush();
    }

    public function delete($object): void
    {
        $this->getObjectManager()->remove($object);
        $this->getObjectManager()->flush();
    }
}
