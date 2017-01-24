<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository;

use AdminPanel\Symfony\AdminBundle\Admin\AbstractElement;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class GenericResourceElement extends AbstractElement implements Element
{
    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_resource';
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteParameters()
    {
        return [
            'element' => $this->getId(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRoute()
    {
        return $this->getRoute();
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRouteParameters()
    {
        return $this->getRouteParameters();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getKey();

    /**
     * @return array
     */
    public function getResourceFormOptions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => null,
        ]);

        $resolver->setAllowedTypes('template', ['null', 'string']);
    }
}
