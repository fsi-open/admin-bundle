<?php

declare(strict_types=1);

namespace FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type;

use Symfony\Component\Validator\Constraints\Url;

class UrlType extends AbstractType
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->constraints[] = new Url();
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceProperty()
    {
        return 'textValue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return 'url';
    }
}
