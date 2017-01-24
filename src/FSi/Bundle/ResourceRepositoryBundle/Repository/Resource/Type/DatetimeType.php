<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type;

class DatetimeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'datetimeValue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return 'datetime';
    }
}
