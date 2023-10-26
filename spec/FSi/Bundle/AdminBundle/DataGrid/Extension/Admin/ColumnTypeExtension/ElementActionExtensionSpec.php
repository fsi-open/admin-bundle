<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use Closure;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\DataGridBundle\DataGrid\ColumnType\Action;
use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;
use FSi\Component\Translatable\LocaleProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElementActionExtensionSpec extends ObjectBehavior
{
    public function let(
        ManagerInterface $manager,
        LocaleProvider $localeProvider
    ): void {
        $localeProvider->getLocale()->willReturn('en');
        $this->beConstructedWith($manager, $localeProvider);
    }

    public function it_is_datagrid_column_extension(): void
    {
        $this->shouldBeAnInstanceOf(ColumnTypeExtensionInterface::class);
    }

    public function it_extends_action_column_type(): void
    {
        self::getExtendedColumnTypes()->shouldReturn([Action::class]);
    }

    public function it_adds_element_id_action_option(
        OptionsResolver $optionsResolver
    ): void {
        $optionsResolver->setDefault('actions', Argument::type(Closure::class))->shouldBeCalled()->willReturn($optionsResolver);

        $this->initOptions($optionsResolver);
    }
}
