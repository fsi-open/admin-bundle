<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement;

abstract class FormElement extends GenericFormElement implements Element
{
    use DataIndexerElementImpl;

    public function save($object): void
    {
        $this->getObjectManager()->persist($object);
        $this->getObjectManager()->flush();
    }
}
