<?php

namespace FSi\Bundle\AdminBundle\Finder;

use Symfony\Component\Finder\Finder;

class AdminClassFinder
{
    const ADMIN_ELEMENT_INTERFACE = 'FSi\\Bundle\\AdminBundle\\Admin\\Element';

    public function findClasses($paths = [])
    {
        $classes = $this->findClassesInPaths($paths);

        return $this->filterAdminClasses($classes);
    }

    /**
     * @param $classes
     * @return array
     */
    private function filterAdminClasses($classes)
    {
        $adminClasses = [];
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
        $includedFiles = [];
        foreach ($searchPaths as $path) {
            foreach ($finder->files()->name('*.php')->in($path) as $file) {
                require_once $file->getRealpath();
                $includedFiles[] = $file->getRealpath();
            }
        }

        $declared = get_declared_classes();

        $classes = [];
        foreach ($declared as $className) {
            $reflection = new \ReflectionClass($className);
            if (in_array($reflection->getFileName(), $includedFiles)) {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}
