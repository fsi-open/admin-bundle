<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\Element;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ContextInterface
{
    public static function getPriority(): int;

    public function supports(string $route, Element $element): bool;

    public function setElement(Element $element): void;

    public function handleRequest(Request $request): ?Response;

    public function hasTemplateName(): bool;

    public function getTemplateName(): ?string;

    public function getData(): array;
}
