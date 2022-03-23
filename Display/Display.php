<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property\ValueFormatter;

interface Display
{
    /**
     * @param mixed $value
     * @param string|null $label
     * @param array<int,ValueFormatter> $valueFormatters
     */
    public function add($value, ?string $label = null, array $valueFormatters = []): self;

    /**
     * @return array<int,Property>
     */
    public function getData(): array;
}
