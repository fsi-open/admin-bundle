<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type;

class IntegerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'integerValue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return 'integer';
    }
}
