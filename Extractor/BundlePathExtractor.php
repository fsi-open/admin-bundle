<?php

namespace FSi\Bundle\AdminBundle\Extractor;

use Symfony\Component\HttpKernel\KernelInterface;

class BundlePathExtractor
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    private $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return array
     */
    public function getBundlePaths()
    {
        $paths = array();
        foreach ($this->kernel->getBundles() as $bundle) {
            $paths[] = $bundle->getPath();
        }

        return $paths;
    }
}
