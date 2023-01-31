<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Component\Translatable\LocaleProvider;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;

use function array_key_exists;

final class TranslationMenuElementVoter implements VoterInterface
{
    private VoterInterface $baseVoter;
    private LocaleProvider $localeProvider;

    public function __construct(VoterInterface $baseVoter, LocaleProvider $localeProvider)
    {
        $this->baseVoter = $baseVoter;
        $this->localeProvider = $localeProvider;
    }

    public function matchItem(ItemInterface $item): ?bool
    {
        $baseElementMatches = $this->baseVoter->matchItem($item);
        if (true !== $baseElementMatches) {
            return $baseElementMatches;
        }

        $match = false;
        $currentLocale = $this->localeProvider->getLocale();
        foreach ((array) $item->getExtra('routes', []) as $testedRoute) {
            $routeParameters = $testedRoute['parameters'];
            if (false === array_key_exists('translatableLocale', $routeParameters)) {
                continue;
            }

            $match = $routeParameters['translatableLocale'] === $currentLocale;
            if (true === $match) {
                break;
            }
        }

        return $match;
    }
}
