<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type;

class DateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'dateValue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return 'date';
    }
}
