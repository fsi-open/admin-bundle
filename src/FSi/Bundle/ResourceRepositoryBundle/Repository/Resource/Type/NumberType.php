<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type;

class NumberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'numberValue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return 'number';
    }

    protected function buildFormOptions()
    {
        $options = parent::buildFormOptions();

        $options = array_merge(
            [
                'precision' => 4
            ],
            $options
        );

        return $options;
    }
}
