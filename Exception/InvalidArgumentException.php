<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Exception;

class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * @param class-string $contextClass
     * @param class-string $expectedClass
     * @param class-string $givenClass
     * @return self
     */
    public static function create(string $contextClass, string $expectedClass, string $givenClass): self
    {
        return new self(sprintf("%s requires %s but instance of %s given", $contextClass, $expectedClass, $givenClass));
    }
}
