<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type;

class BoolType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'boolValue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return 'checkbox';
    }
}
