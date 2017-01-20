<?php

error_reporting(E_ALL | E_STRICT);

$loader = require __DIR__.'/../vendor/autoload.php';

$loader->add('FSi\\Component\\Reflection\\Tests', __DIR__.'\FSi\Component\Reflection\Tests');
$loader->add('FSi\\Component\\Metadata\\Tests', __DIR__.'\FSi\Component\Metadata\Tests');

