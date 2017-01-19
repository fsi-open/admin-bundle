<?php


namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\AbstractElement;
use AdminPanel\Symfony\AdminBundle\Annotation as Admin;
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
    public function setDefaultOptions(OptionsResolver $resolver)
    {
    }
}
