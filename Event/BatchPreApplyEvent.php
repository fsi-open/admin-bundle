<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Event;

class BatchPreApplyEvent extends BatchEvent
{
    /**
     * @var boolean
     */
    private $skip = false;

    public function shouldSkip(): bool
    {
        return $this->skip;
    }

    public function skip(bool $skip = true): void
    {
        $this->skip = $skip;
    }
}
