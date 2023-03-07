<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Twig;

use FSi\Bundle\AdminBundle\Message\FlashMessages;
use FSi\Component\Translatable\LocaleProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TranslatableExtension extends AbstractExtension
{
    private LocaleProvider $localeProvider;

    public function __construct(LocaleProvider $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('translatable_locale', [$this, 'getTranslatableLocale']),
        ];
    }

    public function getTranslatableLocale(): string
    {
        return $this->localeProvider->getLocale();
    }
}
