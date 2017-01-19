<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\Context;

use AdminPanel\Symfony\AdminBundle\Admin\Element;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface ContextBuilderInterface
{
    /**
     * @param string $route
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @return boolean
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\ContextBuilderException
     */
    public function supports($route, Element $element);

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @return \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface
     */
    public function buildContext(Element $element);
}
