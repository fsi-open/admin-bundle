<?php

namespace spec\FSi\Bundle\AdminBundle\Finder;

use FSi\Bundle\AdminBundle\Extractor\BundlePathExtractor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AdminClassFinderSpec extends ObjectBehavior
{
    const FIXTURES_BUNDLE_PATH = '/../../../../../features/fixtures/project/src/FSi/FixturesBundle';

    function let(BundlePathExtractor $bundlePathExtractor)
    {
        $bundlePathExtractor->getBundlePaths()->willReturn(array(
            __DIR__ . self::FIXTURES_BUNDLE_PATH
        ));
        $this->beConstructedWith($bundlePathExtractor);
    }

    function it_find_admin_classes_in_bundles_default_admin_path()
    {
        $this->findClasses()->shouldReturn(array(
            "FSi\\FixturesBundle\\Admin\\News",
            "FSi\\FixturesBundle\\Admin\\CustomNews",
            "FSi\\FixturesBundle\\Admin\\Structure\\HomePage",
            "FSi\\FixturesBundle\\Admin\\AboutUsPage"
        ));
    }

    function it_find_admin_classes_in_default_bundle_paths_and_additional_paths(BundlePathExtractor $bundlePathExtractor)
    {
        $this->beConstructedWith(
            $bundlePathExtractor,
            array(
                __DIR__ . self::FIXTURES_BUNDLE_PATH . '/CustomAdmin'
            )
        );

        $this->findClasses()->shouldReturn(array(
        "FSi\\FixturesBundle\\Admin\\News",
        "FSi\\FixturesBundle\\Admin\\CustomNews",
        "FSi\\FixturesBundle\\Admin\\Structure\\HomePage",
        "FSi\\FixturesBundle\\Admin\\AboutUsPage",
        "FSi\\FixturesBundle\\CustomAdmin\\Contact"
    ));
    }
}
