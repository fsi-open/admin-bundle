<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Display;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Display\Display;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
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
        $resolver->setDefaults(array(
            'template' => null,
        ));

        $resolver->setAllowedTypes('template', array('null', 'string'));
    }

    /**
     * {@inheritdoc}
     */
    public function createDisplayElement($object)
    {
        if (!is_object($object)) {
            throw new RuntimeException("createDisplayElement method accepts only objects.");
        }

        $display = $this->initDisplay($object);
        if (!is_object($display) || !$display instanceof Display) {
            throw new RuntimeException('initDisplay should return instanceof FSi\\Bundle\\AdminBundle\\Display\\Display');
        }

        return $display;
    }

    /**
     * @param mixed $object
     * @return Display
     */
    abstract protected function initDisplay($object);
}
