<?php


namespace AdminPanel\Symfony\AdminBundle\Admin;

use Symfony\Component\HttpFoundation\RequestStack;

interface RequestStackAware
{
    public function setRequestStack(RequestStack $requestStack);
}
