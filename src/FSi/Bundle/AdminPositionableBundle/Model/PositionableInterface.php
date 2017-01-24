<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminPositionableBundle\Model;

interface PositionableInterface
{
    /**
     * Increase position. When list is sorted by position ASC this method move item DOWN the list.
     *
     * @return void
     */
    public function increasePosition();

    /**
     * Decrease position. When list is sorted by position ASC this method move item UP the list.
     *
     * @return void
     */
    public function decreasePosition();
}
