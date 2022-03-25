<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface Element
{
    public function getId(): string;

    public function getRoute(): string;

    /**
     * @return array<string,mixed>
     */
    public function getRouteParameters(): array;

    public function configureOptions(OptionsResolver $resolver): void;

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption(string $name);

    /**
     * @return array<string,mixed>
     */
    public function getOptions(): array;

    public function hasOption(string $name): bool;
}
