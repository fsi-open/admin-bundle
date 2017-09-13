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
    /**
     * ID will appear in routes:
     * - http://example.com/admin/list/{name}
     * - http://example.com/admin/form/{name}
     * etc.
     */
    public function getId(): string;

    /**
     * Return route name that will be used to generate element url in menu.
     */
    public function getRoute(): string;

    /**
     * Return array of parameters.
     * Element id always exists in this array under 'element' key.
     */
    public function getRouteParameters(): array;

    public function configureOptions(OptionsResolver $resolver): void;

    public function getOption(string $name);

    public function getOptions(): array;

    public function hasOption($name): bool;
}
