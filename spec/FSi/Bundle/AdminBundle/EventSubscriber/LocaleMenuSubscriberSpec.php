<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\EventSubscriber;

use Assert\Assertion;
use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

use function expect;

class LocaleMenuSubscriberSpec extends ObjectBehavior
{
    public function let(TranslatorInterface $translator, RequestStack $requestStack): void
    {
        $translator->trans(
            'admin.language.current',
            ['%locale%' => 'Deutsch'],
            'FSiAdminBundle'
        )->willReturn('translated');

        $this->beConstructedWith($translator, $requestStack, ['en', 'de']);
    }

    public function it_should_build_locale_menu(
        MenuEvent $event,
        RequestStack $requestStack,
        Request $request
    ): void {
        $menu = new Item();
        $event->getMenu()->willReturn($menu);
        $requestStack->getCurrentRequest()->willReturn($request);
        $request->getLocale()->willReturn('de');
        $request->getUri()->willReturn('uri_to_redirect_to');

        $this->createLocaleMenu($event);

        $rootItem = $menu->getChildren()['admin-locale'];

        $localeItems = $rootItem->getChildren();

        $enItem = $localeItems['admin-locale.en'];
        $deItem = $localeItems['admin-locale.de'];

        Assertion::same($enItem->getLabel(), 'Englisch');
        Assertion::same($enItem->getRoute(), 'fsi_admin_locale');
        Assertion::same($enItem->getRouteParameters(), ['_locale' => 'en', 'redirect_uri' => 'uri_to_redirect_to']);
        Assertion::same($enItem->getOptions(), ['attr' => ['id' => null, 'class' => null]]);

        Assertion::same($deItem->getLabel(), 'Deutsch');
        Assertion::same($deItem->getRoute(), 'fsi_admin_locale');
        Assertion::same($deItem->getRouteParameters(), ['_locale' => 'de', 'redirect_uri' => 'uri_to_redirect_to']);
        Assertion::same($deItem->getOptions(), ['attr' => ['id' => null, 'class' => 'active']]);
    }
}
