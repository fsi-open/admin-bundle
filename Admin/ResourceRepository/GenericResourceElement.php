<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Request\Parameters;
use FSi\Component\Translatable\LocaleProvider;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_merge;

abstract class GenericResourceElement extends AbstractElement implements Element
{
    private LocaleProvider $localeProvider;

    public function getRoute(): string
    {
        return 'fsi_admin_resource';
    }

    public function getRouteParameters(): array
    {
        return array_merge(
            parent::getRouteParameters(),
            [Parameters::TRANSLATABLE_LOCALE => $this->localeProvider->getLocale()]
        );
    }

    public function getSuccessRoute(): string
    {
        return $this->getRoute();
    }

    public function getSuccessRouteParameters(): array
    {
        return $this->getRouteParameters();
    }

    public function setLocaleProvider(LocaleProvider $localeProvider): void
    {
        $this->localeProvider = $localeProvider;
    }


    abstract public function getKey(): string;

    public function getResourceFormOptions(): array
    {
        return [];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'template' => null,
        ]);

        $resolver->setAllowedTypes('template', ['null', 'string']);
    }
}
