<?php

namespace spec\FSi\Bundle\AdminBundle\Finder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AdminClassFinderSpec extends ObjectBehavior
{
    const FIXTURES_BUNDLE_PATH = '/../../../../../spec/fixtures';

    function it_find_admin_classes_in_default_path()
    {
        $paths = array(__DIR__ . self::FIXTURES_BUNDLE_PATH . '/Admin');
        /* We can't just check if result is an array with following values because it might be in other order */
        $this->findClasses($paths)->shouldHaveCount(5);
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\CRUDElement");
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DoctrineElement");
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\SimpleAdminElement");
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DataGridAwareElement");
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DataSourceAwareElement");
    }

    function it_find_admin_classes_in_additional_paths()
    {
        $paths = array(
            __DIR__ . self::FIXTURES_BUNDLE_PATH . '/Admin',
            __DIR__ . self::FIXTURES_BUNDLE_PATH . '/CustomAdmin'
        );

        $this->findClasses($paths)->shouldHaveCount(6);
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\CRUDElement");
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DoctrineElement");
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\SimpleAdminElement");
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DataGridAwareElement");
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DataSourceAwareElement");
        $this->findClasses($paths)->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\CustomAdmin\\SimpleAdminElement");
    }
}
