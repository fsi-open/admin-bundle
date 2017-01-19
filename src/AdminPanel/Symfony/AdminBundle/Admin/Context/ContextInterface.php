<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface ContextInterface
{
    /**
     * @param string $route
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @return boolean
     */
    public function supports($route, Element $element);

    /**
     * @param Element $element
     */
    public function setElement(Element $element);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(Request $request);

    /**
     * @return boolean
     */
    public function hasTemplateName();

    /**
     * @return string
     */
    public function getTemplateName();

    /**
     * @return array
     */
    public function getData();
}
