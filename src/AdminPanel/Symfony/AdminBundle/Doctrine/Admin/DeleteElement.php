<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericDeleteElement;

abstract class DeleteElement extends GenericDeleteElement implements Element
{
    use DataIndexerElementImpl;

    /**
     * @inheritdoc
     */
    public function delete($object)
    {
        $this->getObjectManager()->remove($object);
        $this->getObjectManager()->flush();
    }
}
