<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\spec\fixtures\Admin;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Annotation as Admin;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @Admin\Element
 */
class SimpleAdminElement extends AbstractElement
{
    /**
     * ID will appear in routes:
     * - http://example.com/admin/{name}/list
     * - http://example.com/admin/{name}/edit
     * etc.
     *
     * @return string
     */
    public function getId()
    {
        return 'simple_admin_element';
    }

    /**
     * Name is a simple string that can be translated.
     *
     * @return string
     */
    public function getName()
    {
        return 'simple.admin.element';
    }

    /**
     * Return route name that will be used to generate element url in menu.
     *
     * @return string
     */
    public function getRoute()
    {
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     * @return mixed
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
