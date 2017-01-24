<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundleBundle\Tests\Fixtures;

class TwigRuntimeLoader implements \Twig_RuntimeLoaderInterface
{
    private $instances = [];

    public function __construct(array $instances)
    {
        foreach ($instances as $instance) {
            $this->instances[get_class($instance)] = $instance;
        }
    }

    /**
     * @inheritdoc
     */
    public function load($class)
    {
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        return null;
    }
}
