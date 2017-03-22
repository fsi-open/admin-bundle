<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
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
