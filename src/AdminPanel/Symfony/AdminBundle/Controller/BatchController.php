<?php


namespace AdminPanel\Symfony\AdminBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class BatchController extends ControllerAbstract
{
    /**
     * @ParamConverter("element", class="\AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement")
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function batchAction(BatchElement $element, Request $request)
    {
        return $this->handleRequest($element, $request, 'fsi_admin_batch');
    }
}
