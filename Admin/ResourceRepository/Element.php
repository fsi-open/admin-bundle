<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Admin\LocaleProviderAware;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;

interface Element extends LocaleProviderAware, RedirectableElement
{
    public function getKey(): string;

    /**
     * @return array<string,mixed>
     */
    public function getResourceFormOptions(): array;

    public function save(ResourceValue $resource): void;

    public function getResourceValueRepository(): ResourceValueRepository;
}
