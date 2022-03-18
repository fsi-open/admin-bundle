<?php

declare(strict_types=1);

use FSi\AppKernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__ . '/../../../../vendor/autoload.php';
Debug::enable();

$kernel = new AppKernel('test', true);
Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
