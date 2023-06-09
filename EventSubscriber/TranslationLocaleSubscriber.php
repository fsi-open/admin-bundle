<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\EventSubscriber;

use FSi\Bundle\AdminBundle\Event\AdminContextPreCreateEvent;
use FSi\Bundle\AdminBundle\Request\Parameters;
use FSi\Component\Translatable\LocaleProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TranslationLocaleSubscriber implements EventSubscriberInterface
{
    private LocaleProvider $localeProvider;

    public function __construct(LocaleProvider $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

    public static function getSubscribedEvents(): array
    {
        return [AdminContextPreCreateEvent::class => 'setLocale'];
    }

    public function setLocale(AdminContextPreCreateEvent $event): void
    {
        $locale = $event->getRequest()->attributes->get(Parameters::TRANSLATABLE_LOCALE);
        if (null === $locale) {
            return;
        }

        $this->localeProvider->saveLocale($locale);
    }
}
