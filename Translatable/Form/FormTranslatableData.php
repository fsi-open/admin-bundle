<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\Form;

use RuntimeException;

final class FormTranslatableData
{
    private bool $translatable;
    private ?object $data;

    public function __construct(bool $translatable, ?object $data)
    {
        if (false === $translatable && null !== $data) {
            throw new RuntimeException('Cannot pass data for non translatable form property.');
        }

        $this->translatable = $translatable;
        $this->data = $data;
    }

    public function isTranslatable(): bool
    {
        return $this->translatable;
    }

    public function getData(): ?object
    {
        return $this->data;
    }
}
