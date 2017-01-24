<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class FormController extends ControllerAbstract
{
    /**
     * @ParamConverter("element", class="\AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement")
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function formAction(FormElement $element, Request $request)
    {
        return $this->handleRequest($element, $request, 'fsi_admin_form');
    }
}
