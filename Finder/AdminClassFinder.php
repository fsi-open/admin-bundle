<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Finder;

use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use FSi\Bundle\AdminBundle\Admin\Element;

/**
 * @deprecated since 3.0
 */
class AdminClassFinder
{
    /**
     * @param array<string> $paths
     * @return array<string>
     */
    public function findClasses(array $paths = []): array
    {
        $classes = $this->findClassesInPaths($paths);

        return $this->filterAdminClasses($classes);
    }

    private function filterAdminClasses(array $classes): array
    {
        $adminClasses = [];
        foreach ($classes as $className) {
            $classImplements = class_implements($className);
            if (true === in_array(Element::class, $classImplements, true)) {
                $adminClasses[] = $className;
            }
        }

        return $adminClasses;
    }

    private function findClassesInPaths(array $searchPaths): array
    {
        $finder = new Finder();
        $includedFiles = [];
        foreach ($searchPaths as $path) {
            /** @var SplFileInfo[] $files */
            $files = $finder->files()->name('*.php')->in($path);
            foreach ($files as $file) {
                require_once $file->getRealPath();
                $includedFiles[] = $file->getRealPath();
            }
        }

        $declared = get_declared_classes();

        $classes = [];
        foreach ($declared as $className) {
            $reflection = new ReflectionClass($className);
            if (true === in_array($reflection->getFileName(), $includedFiles, true)) {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}
