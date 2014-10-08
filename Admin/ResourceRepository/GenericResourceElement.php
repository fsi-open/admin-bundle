<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'template' => null,
            'title' => 'resource.title',
        ));

        $resolver->setAllowedTypes(array(
            'template' => array('null', 'string')
        ));
    }
}
