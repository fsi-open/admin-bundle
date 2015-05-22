<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\FormElement;
use Symfony\Component\Form\FormFactoryInterface;

class SubscriberForm extends FormElement
{
    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Subscriber';
    }

    public function getId()
    {
        return 'subscriber_form';
    }

    public function getName()
    {
        return 'admin.subscriber.name';
    }

    public function getSuccessRoute()
    {
        return 'fsi_admin_list';
    }

    public function getSuccessRouteParameters()
    {
        return array(
            'element' => 'subscriber',
            'current_uri' => $this->getRequestStack()->getCurrentRequest()->getUri()
        );
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $builder = $factory->createNamedBuilder('subscriber', 'form', $data, array(
            'data_class' => $this->getClassName()
        ));

        $builder->add('email', 'email', array(
            'label' => 'admin.subscriber.list.email',
        ));
        $builder->add('created_at', 'date', array(
            'label' => 'admin.subscriber.list.created_at',
            'widget' => 'single_text'
        ));
        $builder->add('active', 'checkbox', array(
            'label' => 'admin.subscriber.list.active',
        ));

        return $builder->getForm();
    }
}
