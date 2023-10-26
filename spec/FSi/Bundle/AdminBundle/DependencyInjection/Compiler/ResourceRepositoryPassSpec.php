<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\DependencyInjection\Compiler;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\ResourceFormBuilder;
use FSi\Bundle\AdminBundle\EventSubscriber\TranslationLocaleMenuSubscriber;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ResourceRepositoryPassSpec extends ObjectBehavior
{
    public function it_does_nothing_when_there_is_no_resource_extension(
        ContainerBuilder $container,
        Definition $translationLocaleMenuSubscriberDefinition
    ): void {
        $translationLocaleMenuSubscriberDefinition
            ->setArgument(
                '$resourceRepositoryClass',
                '%fsi_resource_repository.resource.value.class%'
            )
            ->shouldBeCalled()
            ->willReturn($translationLocaleMenuSubscriberDefinition)
        ;
        $container->hasExtension('fsi_resource_repository')->willReturn(true);
        $container->removeDefinition(Argument::any())->shouldNotBeCalled();
        $container
            ->getDefinition(TranslationLocaleMenuSubscriber::class)
            ->shouldBeCalled()
            ->willReturn($translationLocaleMenuSubscriberDefinition)
        ;

        $this->process($container);
    }

    public function it_removes_resource_repository_context_when_there_is_no_extension(ContainerBuilder $container): void
    {
        $container->hasExtension('fsi_resource_repository')->willReturn(false);
        $container->removeDefinition(ResourceRepositoryContext::class)->shouldBeCalled();
        $container->removeDefinition(MapBuilder::class)->shouldBeCalled();
        $container->removeDefinition(ResourceFormBuilder::class)->shouldBeCalled();
        $this->process($container);
    }
}
