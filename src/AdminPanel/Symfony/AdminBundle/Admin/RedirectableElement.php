<?php


namespace AdminPanel\Symfony\AdminBundle\Admin;

interface RedirectableElement extends Element
{
    /**
     * Return route name that will be used to redirect after successful form handling.
     *
     * @return string
     */
    public function getSuccessRoute();

    /**
     * Return array of parameters used with route returned from getSuccessRoute().
     *
     * @return array
     */
    public function getSuccessRouteParameters();
}
