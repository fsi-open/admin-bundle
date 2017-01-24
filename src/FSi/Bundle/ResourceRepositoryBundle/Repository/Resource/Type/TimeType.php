<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type;

class TimeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'timeValue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return 'time';
    }
}
