<?php

namespace spec\FSi\Bundle\AdminBundle\Extractor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

class BundlePathExtractorSpec extends ObjectBehavior
{
    function let(KernelInterface $kernel, Bundle $fooBundle, Bundle $barBundle)
    {
        $kernel->getBundles()->willReturn(array(
            $fooBundle,
            $barBundle
        ));
        $fooBundle->getPath()->willReturn('src/FSi/Bundle/FooBundle');
        $barBundle->getPath()->willReturn('src/FSi/Bundle/BarBundle');
        $this->beConstructedWith($kernel);
    }

    function it_return_paths_to_all_bundle_registered_in_kernel()
    {
        $this->getBundlePaths()->shouldReturn(array(
            'src/FSi/Bundle/FooBundle',
            'src/FSi/Bundle/BarBundle'
        ));
    }
}
