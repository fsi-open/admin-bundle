<?php

namespace FSi\FixturesBundle\CustomAdmin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;

class Contact extends ResourceElement
{
    /**
     * @return string
     */
    public function getClassName()
    {
    }

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
    }

    /**
     * Name is a simple string that can be translated.
     *
     * @return string
     */
    public function getName()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
    }
}
