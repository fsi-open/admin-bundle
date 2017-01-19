<?php


namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\RequestStackAware;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStackAwareElement extends SimpleAdminElement implements RequestStackAware
{
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
}
