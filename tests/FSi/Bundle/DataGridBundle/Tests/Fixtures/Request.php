<?php

namespace FSi\Bundle\DataGridBundle\Tests\Fixtures;

use Symfony\Component\HttpFoundation\Request as BaseRequest;

class Request extends BaseRequest
{
    const ABSOLUTE_URI = 'http://example.com/?test=1&test=2';
    const RELATIVE_URI = '/?test=1&test=2';

    public function __construct()
    {
    }

    public function getUri()
    {
        return self::ABSOLUTE_URI;
    }

    public function getRequestUri()
    {
        return self::RELATIVE_URI;
    }
}
