<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\Display;

use AdminPanel\Symfony\AdminBundle\Admin\AbstractElement;
use AdminPanel\Symfony\AdminBundle\Display\Display;
use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class GenericDisplayElement extends AbstractElement implements Element
{
    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_display';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     * @return mixed
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => null,
        ]);

        $resolver->setAllowedTypes('template', ['null', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public function createDisplay($object)
    {
        if (!is_object($object)) {
            throw new RuntimeException("createDisplay method accepts only objects.");
        }

        $display = $this->initDisplay($object);
        if (!is_object($display) || !$display instanceof Display) {
            throw new RuntimeException('initDisplay should return instanceof AdminPanel\\Symfony\\AdminBundle\\Display\\Display');
        }

        return $display;
    }

    /**
     * @param mixed $object
     * @return Display
     */
    abstract protected function initDisplay($object);
}
