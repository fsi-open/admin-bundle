<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\EventSubscriber;

use Assert\Assertion;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Element;
use FSi\Bundle\AdminBundle\Event\MenuToolsEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use FSi\Component\Translatable\ConfigurationResolver;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function array_key_exists;
use function array_merge;
use function count;
use function is_string;

final class TranslationLocaleMenuSubscriber implements EventSubscriberInterface
{
    private ManagerInterface $elementManager;
    private ConfigurationResolver $translatableConfigurationResolver;
    private TranslatorInterface $translator;
    private UrlGeneratorInterface $urlGenerator;
    private RequestMatcherInterface $requestMatcher;
    private RequestStack $requestStack;
    private ?Request $request;
    /**
     * @var array<string>
     */
    private array $locales;

    /**
     * @param array<string> $locales
     */
    public function __construct(
        ManagerInterface $elementManager,
        ConfigurationResolver $translatableConfigurationResolver,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        RequestMatcherInterface $requestMatcher,
        RequestStack $requestStack,
        array $locales
    ) {
        $this->elementManager = $elementManager;
        $this->translatableConfigurationResolver = $translatableConfigurationResolver;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->requestMatcher = $requestMatcher;
        $this->requestStack = $requestStack;
        $this->locales = $locales;
        $this->request = null;
    }

    /**
     * @return array<class-string<MenuToolsEvent>, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [MenuToolsEvent::class => 'createTranslationLocaleMenu'];
    }

    public function createTranslationLocaleMenu(MenuToolsEvent $event): void
    {
        if (2 > count($this->locales)) {
            return;
        }

        $isCurrentElementTranslatable = $this->isCurrentElementTranslatable();
        $rootItem = $this->createRootItem($isCurrentElementTranslatable);
        $event->getMenu()->addChild($rootItem);

        if (false === $isCurrentElementTranslatable) {
            return;
        }

        $this->populateTranslationLocaleMenu($rootItem);
    }

    private function createRootItem(bool $isCurrentElementTranslatable): Item
    {
        $translation = new Item('translation-locale');
        $translation->setLabel(
            $this->translator->trans(
                'admin.translation_locale.title',
                ['%locale%' => $this->getRequest()->attributes->get('translatableLocale')],
                'FSiAdminBundle'
            )
        );

        $translation->setOptions([
            'attr' => [
                'id' => 'translatable-switcher',
                'class' => false === $isCurrentElementTranslatable ? 'disabled' : ''
            ],
        ]);

        return $translation;
    }

    private function populateTranslationLocaleMenu(Item $menu): void
    {
        $requestParameters = $this->getRequestParameters();
        $route = $this->getRequest()->get('_route');

        if (true === array_key_exists('redirect_uri', $requestParameters)) {
            $redirectRequest = $this->createRedirectRequest($requestParameters['redirect_uri']);
        } else {
            $redirectRequest = null;
        }

        foreach ($this->locales as $locale) {
            if (null !== $redirectRequest) {
                try {
                    $requestParameters['redirect_uri'] = $this->generateRequestUriForLocale(
                        $redirectRequest,
                        $locale
                    );
                } catch (ResourceNotFoundException $e) {
                }
            }

            $requestParameters['translatableLocale'] = $locale;
            $localeItem = new RoutableItem(
                "translation-locale.{$locale}",
                $route,
                $requestParameters
            );
            $localeItem->setLabel($this->localeToLangaugeName($locale));

            $menu->addChild($localeItem);
        }
    }

    private function createRedirectRequest(string $redirectUri): ?Request
    {
        $redirectUrlParts = parse_url($redirectUri);
        if (false === $redirectUrlParts || true === array_key_exists('host', $redirectUrlParts)) {
            return null;
        }

        $request = $this->getRequest();
        $redirectServer = [
            'SCRIPT_FILENAME' => $request->server->get('SCRIPT_FILENAME'),
            'PHP_SELF' => $request->server->get('PHP_SELF'),
            'REQUEST_URI' => $redirectUrlParts['path'] ?? null,
        ];

        if (true === array_key_exists('query', $redirectUrlParts)) {
            $redirectServer['QUERY_STRING'] = $redirectUrlParts['query'];
        }

        return new Request([], [], [], [], [], $redirectServer);
    }

    private function generateRequestUriForLocale(Request $redirectRequest, string $locale): string
    {
        $parameters = $this->requestMatcher->matchRequest($redirectRequest);
        if (true === array_key_exists('translatableLocale', $parameters)) {
            $parameters['translatableLocale'] = $locale;
        }

        $route = $parameters['_route'];
        unset($parameters['_route']);
        unset($parameters['_controller']);

        $requestUri = $this->urlGenerator->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
        $queryString = $redirectRequest->getQueryString();
        if (null !== $queryString && '' !== $queryString) {
            $requestUri .= "?{$queryString}";
        }

        return $requestUri;
    }

    private function isCurrentElementTranslatable(): bool
    {
        $request = $this->getRequest();
        if (false === $request->attributes->has('element')) {
            return false;
        }

        $element = $request->attributes->get('element');
        if (true === is_string($element)) {
            if (false === $this->elementManager->hasElement($element)) {
                return false;
            }

            $element = $this->elementManager->getElement($element);
        }

        if (false === $element instanceof Element) {
            return false;
        }

        return $this->translatableConfigurationResolver->isTranslatable($element->getClassName());
    }

    private function localeToLangaugeName(string $locale): string
    {
        return Languages::getName($locale, $this->getRequest()->attributes->get('_locale'));
    }

    /**
     * @return array<string, mixed>
     */
    private function getRequestParameters(): array
    {
        return array_merge(
            $this->getRequest()->get('_route_params'),
            $this->getRequest()->query->all()
        );
    }

    private function getRequest(): Request
    {
        if (null === $this->request) {
            $this->request = $this->requestStack->getCurrentRequest();
            Assertion::notNull($this->request);
        }

        return $this->request;
    }
}
