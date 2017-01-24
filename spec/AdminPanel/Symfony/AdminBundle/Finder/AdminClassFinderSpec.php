<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Finder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AdminClassFinderSpec extends ObjectBehavior
{
    const DOUBLES_BUNDLE_PATH = '/../../../../../src/AdminPanel/Symfony/AdminBundle/Tests/Doubles';

    public function it_find_admin_classes_in_default_path()
    {
        $paths = [__DIR__ . self::DOUBLES_BUNDLE_PATH . '/Admin'];
        /* We can't just check if result is an array with following values because it might be in other order */
        $this->findClasses($paths)->shouldHaveCount(6);
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\CRUDElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\DoctrineElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\SimpleAdminElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\DataGridAwareElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\DataSourceAwareElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\RequestStackAwareElement");
    }

    public function it_find_admin_classes_in_additional_paths()
    {
        $paths = [
            __DIR__ . self::DOUBLES_BUNDLE_PATH . '/Admin',
            __DIR__ . self::DOUBLES_BUNDLE_PATH . '/CustomAdmin'
        ];

        $this->findClasses($paths)->shouldHaveCount(7);
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\CRUDElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\DoctrineElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\SimpleAdminElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\DataGridAwareElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\DataSourceAwareElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\RequestStackAwareElement");
        $this->findClasses($paths)->shouldContain("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\CustomAdmin\\SimpleAdminElement");
    }
}
