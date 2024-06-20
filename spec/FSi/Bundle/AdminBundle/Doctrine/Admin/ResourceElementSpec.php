<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use FSi\Bundle\AdminBundle\spec\fixtures\MyResourceElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;
use FSi\Component\Translatable\LocaleProvider;
use Mockery;
use PhpSpec\ObjectBehavior;

class ResourceElementSpec extends ObjectBehavior
{
    public function let(LocaleProvider $localeProvider, ManagerRegistry $registry): void
    {
        $localeProvider->getLocale()->willReturn('en');
        $this->beAnInstanceOf(MyResourceElement::class);
        $this->beConstructedWith([]);
        $this->setManagerRegistry($registry);
        $this->setLocaleProvider($localeProvider);
    }

    public function it_return_repository(
        ManagerRegistry $registry,
        ObjectManager $om
    ): void {
        $repository = Mockery::mock(ObjectRepository::class, ResourceValueRepository::class);
        $registry->getManagerForClass('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($om);
        $om->getRepository('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($repository);

        $this->getRepository()->shouldReturn($repository);
    }

    public function it_throws_exception_when_repository_does_not_implement_resource_value_repository(
        ManagerRegistry $registry,
        ObjectManager $om,
        ObjectRepository $repository
    ): void {
        $registry->getManagerForClass('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($om);
        $registry->getRepository('FSi\Bundle\DemoBundle\Entity\Resource')->willReturn($repository);

        $this->shouldThrow()->during('getRepository');
    }
}
