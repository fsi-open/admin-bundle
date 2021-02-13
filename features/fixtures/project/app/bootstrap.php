<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$file = __DIR__.'/../../../../vendor/autoload.php';
if (false === file_exists($file)) {
    throw new \RuntimeException('Install the dependencies to run the test suite.');
}

$loader = require $file;
AnnotationRegistry::registerLoader([$loader, 'loadClass']);
