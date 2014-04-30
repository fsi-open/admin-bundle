<?php

namespace spec\FSi\Bundle\AdminBundle\Finder;

use FSi\Bundle\AdminBundle\Extractor\BundlePathExtractor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AdminClassFinderSpec extends ObjectBehavior
{
    const FIXTURES_BUNDLE_PATH = '/../../../../../spec/fixtures';

    function let(BundlePathExtractor $bundlePathExtractor)
    {
        $bundlePathExtractor->getBundlePaths()->willReturn(array(
            __DIR__ . self::FIXTURES_BUNDLE_PATH
        ));
        $this->beConstructedWith($bundlePathExtractor);
    }

    function it_find_admin_classes_in_bundles_default_admin_path()
    {
        /* We can't just check if result is an array with following values because it might be in other order */
        $this->findClasses()->shouldHaveCount(5);
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\CRUDElement");
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DoctrineElement");
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\SimpleAdminElement");
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DataGridAwareElement");
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DataSourceAwareElement");
    }

    function it_find_admin_classes_in_default_bundle_paths_and_additional_paths(BundlePathExtractor $bundlePathExtractor)
    {
        $this->beConstructedWith(
            $bundlePathExtractor,
            array(
                __DIR__ . self::FIXTURES_BUNDLE_PATH . '/CustomAdmin'
            )
        );

        $this->findClasses()->shouldHaveCount(6);
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\CRUDElement");
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DoctrineElement");
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\SimpleAdminElement");
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DataGridAwareElement");
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\DataSourceAwareElement");
        $this->findClasses()->shouldContain("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\CustomAdmin\\SimpleAdminElement");
    }
}
