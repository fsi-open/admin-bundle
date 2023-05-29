<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\Menu\Voter;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\Routing\RequestContext;

use function array_key_exists;

final class TranslatableElementVoter implements VoterInterface
{
    private VoterInterface $menuElementVoter;
    private RequestContext $requestContext;

    public function __construct(VoterInterface $menuElementVoter, RequestContext $requestContext)
    {
        $this->menuElementVoter = $menuElementVoter;
        $this->requestContext = $requestContext;
    }

    public function matchItem(ItemInterface $item): ?bool
    {
        $elementMatch = $this->menuElementVoter->matchItem($item);
        if (false === $elementMatch || null === $elementMatch) {
            return $elementMatch;
        }

        $currentLocale = $this->requestContext->getParameter('translatableLocale');
        if (null === $currentLocale) {
            return $elementMatch;
        }

        $routes = (array) $item->getExtra('routes', []);
        foreach ($routes as $testedRoute) {
            $routeParameters = $testedRoute['parameters'];
            if (false === array_key_exists('translatableLocale', $routeParameters)) {
                continue;
            }

            return $routeParameters['translatableLocale'] === $currentLocale;
        }

        return $elementMatch;
    }
}
