<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin;

use Symfony\Component\HttpFoundation\RequestStack;

interface RequestStackAware
{
    public function setRequestStack(RequestStack $requestStack);
}
