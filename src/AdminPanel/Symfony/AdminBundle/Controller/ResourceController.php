<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class ResourceController extends ControllerAbstract
{
    /**
     * @ParamConverter("element", class="\AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Element")
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Element $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resourceAction(ResourceRepository\Element $element, Request $request)
    {
        return $this->handleRequest($element, $request, 'fsi_admin_resource');
    }
}
