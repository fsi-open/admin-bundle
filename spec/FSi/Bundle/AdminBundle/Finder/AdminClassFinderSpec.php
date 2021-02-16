<?php

namespace spec\FSi\Bundle\AdminBundle\Finder;

use PhpSpec\ObjectBehavior;
use FSi\Bundle\AdminBundle\spec\fixtures\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\spec\fixtures\CustomAdmin;
use FSi\Bundle\AdminBundle\spec\fixtures\Admin\DoctrineElement;
use FSi\Bundle\AdminBundle\spec\fixtures\Admin\SimpleAdminElement;
use FSi\Bundle\AdminBundle\spec\fixtures\Admin\RequestStackAwareElement;

class AdminClassFinderSpec extends ObjectBehavior
{
    public const FIXTURES_BUNDLE_PATH = '/../../../../../spec/fixtures';

    public function it_find_admin_classes_in_default_path(): void
    {
        $paths = [__DIR__ . self::FIXTURES_BUNDLE_PATH . '/Admin'];
        /* We can't just check if result is an array with following values because it might be in other order */
        $this->findClasses($paths)->shouldHaveCount(4);
        $this->findClasses($paths)->shouldContain(CRUDElement::class);
        $this->findClasses($paths)->shouldContain(DoctrineElement::class);
        $this->findClasses($paths)->shouldContain(SimpleAdminElement::class);
        $this->findClasses($paths)->shouldContain(RequestStackAwareElement::class);
    }

    public function it_find_admin_classes_in_additional_paths(): void
    {
        $paths = [
            __DIR__ . self::FIXTURES_BUNDLE_PATH . '/Admin',
            __DIR__ . self::FIXTURES_BUNDLE_PATH . '/CustomAdmin',
        ];

        $this->findClasses($paths)->shouldHaveCount(5);
        $this->findClasses($paths)->shouldContain(CRUDElement::class);
        $this->findClasses($paths)->shouldContain(DoctrineElement::class);
        $this->findClasses($paths)->shouldContain(SimpleAdminElement::class);
        $this->findClasses($paths)->shouldContain(RequestStackAwareElement::class);
        $this->findClasses($paths)->shouldContain(CustomAdmin\SimpleAdminElement::class);
    }
}
