<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Component\Translatable\LocaleProvider;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_merge;

/**
 * @template T of array<string,mixed>|object
 * @template-implements BatchElement<T>
 */
abstract class GenericBatchElement extends AbstractElement implements BatchElement
{
    private LocaleProvider $localeProvider;

    public function getRoute(): string
    {
        return 'fsi_admin_batch';
    }

    public function getRouteParameters(): array
    {
        return array_merge(parent::getRouteParameters(), ['translatableLocale' => $this->localeProvider->getLocale()]);
    }

    public function setLocaleProvider(LocaleProvider $localeProvider): void
    {
        $this->localeProvider = $localeProvider;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    public function getSuccessRoute(): string
    {
        return 'fsi_admin';
    }

    public function getSuccessRouteParameters(): array
    {
        return [];
    }
}
