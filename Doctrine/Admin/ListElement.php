<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericListElement;

abstract class ListElement extends GenericListElement implements Element
{
    use DataIndexerElementImpl;

    public function saveDataGrid(): void
    {
        $this->getObjectManager()->flush();
    }
}
