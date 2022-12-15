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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template T of array<string,mixed>|object
 * @template-implements BatchElement<T>
 */
abstract class GenericBatchElement extends AbstractElement implements BatchElement
{
    public function getRoute(): string
    {
        return 'fsi_admin_batch';
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
