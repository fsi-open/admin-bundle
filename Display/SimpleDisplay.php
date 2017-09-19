<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Display;

class SimpleDisplay implements Display
{
    /**
     * @var Property[]
     */
    private $data = [];

    /**
     * {@inheritdoc}
     */
    public function add($value, ?string $label = null, array $valueFormatters = []): Display
    {
        $this->data[] = new Property($value, $label, $valueFormatters);

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
