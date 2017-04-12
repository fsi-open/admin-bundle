<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class GenericBatchElement extends AbstractElement implements BatchElement
{
    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_batch';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRoute()
    {
        return 'fsi_admin';
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRouteParameters()
    {
        return [];
    }
}
