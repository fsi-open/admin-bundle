<?php

namespace FSi\Bundle\AdminBundle\Finder;

use FSi\Bundle\AdminBundle\Extractor\BundlePathExtractor;
use Symfony\Component\Finder\Finder;

class AdminClassFinder
{
    const ADMIN_ELEMENT_INTERFACE = 'FSi\\Bundle\\AdminBundle\\Admin\\ElementInterface';

    /**
     * @var BundlePathExtractor
     */
    private $bundlePathExtractor;
    /**
     * @var array
     */
    private $paths;

    public function __construct(BundlePathExtractor $bundlePathExtractor, $paths = array())
    {
        $this->bundlePathExtractor = $bundlePathExtractor;
        $this->paths = $paths;
    }

    public function findClasses()
    {
        $searchPaths = array_merge($this->getDefaultBundlesAdminPaths(), $this->paths);
        $classes = $this->findClassesInPaths($searchPaths);

        return $this->filterAdminClasses($classes);
    }

    private function getDefaultBundlesAdminPaths()
    {
        $paths = array();
        foreach ($this->bundlePathExtractor->getBundlePaths() as $path) {
            if (is_dir($path . '/Admin')) {
                $paths[] = $path . '/Admin';
            }
        }

        return $paths;
    }

    /**
     * @param $classes
     * @return array
     */
    private function filterAdminClasses($classes)
    {
        $adminClasses = array();
        foreach ($classes as $className) {
            $classImplements = class_implements($className);
            if (in_array(self::ADMIN_ELEMENT_INTERFACE, $classImplements)) {
                $adminClasses[] = $className;
            }
        }

        return $adminClasses;
    }

    /**
     * @param $searchPaths
     * @return array
     */
    private function findClassesInPaths($searchPaths)
    {
        $finder = new Finder();
        $includedFiles = array();
        foreach ($searchPaths as $path) {
            foreach ($finder->files()->name('*.php')->in($path) as $file) {
                require_once $file->getRealpath();
                $includedFiles[] = $file->getRealpath();
            }
        }

        $declared = get_declared_classes();

        $classes = array();
        foreach ($declared as $className) {
            $reflection = new \ReflectionClass($className);
            if (in_array($reflection->getFileName(), $includedFiles)) {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}
