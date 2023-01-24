<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\EventSubscriber;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class LocaleMenuSubscriberSpec extends ObjectBehavior
{
    public function let(TranslatorInterface $translator, RequestStack $requestStack): void
    {
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

        expect($enItem->getLabel())->toBe('Englisch');
        expect($enItem->getRoute())->toBe('fsi_admin_locale');
        expect($enItem->getRouteParameters())->toBe(['_locale' => 'en', 'redirect_uri' => 'uri_to_redirect_to']);
        expect($enItem->getOptions())->toBe(['attr' => ['id' => null, 'class' => null]]);

        expect($deItem->getLabel())->toBe('Deutsch');
        expect($deItem->getRoute())->toBe('fsi_admin_locale');
        expect($deItem->getRouteParameters())->toBe(['_locale' => 'de', 'redirect_uri' => 'uri_to_redirect_to']);
        expect($deItem->getOptions())->toBe(['attr' => ['id' => null, 'class' => 'active']]);
    }
}
