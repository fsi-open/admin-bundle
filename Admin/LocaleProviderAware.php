<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin;

use FSi\Component\Translatable\LocaleProvider;

interface LocaleProviderAware
{
    public function setLocaleProvider(LocaleProvider $localeProvider): void;
}
