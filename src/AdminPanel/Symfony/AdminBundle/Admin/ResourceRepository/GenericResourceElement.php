<?php


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
        return array(
            'element' => $this->getId(),
        );
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
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'template' => null,
        ));

        $resolver->setAllowedTypes('template', array('null', 'string'));
    }
}
