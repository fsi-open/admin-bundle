<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type;

class FSiRemovableFileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'fileValue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return 'fsi_removable_file';
    }

    protected function buildFormOptions()
    {
        $options = parent::buildFormOptions();

        if (isset($options['constraints'])) {
            $options['file_options'] = array_merge(
                isset($options['file_options']) ? $options['file_options'] : [],
                ['constraints' => $options['constraints']]
            );
            unset($options['constraints']);
        }

        return $options;
    }
}
