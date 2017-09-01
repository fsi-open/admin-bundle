<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Event;

class BatchPreApplyEvent extends BatchEvent
{
    /**
     * @var boolean
     */
    private $skip = false;

    /**
     * @return boolean
     */
    public function shouldSkip()
    {
        return $this->skip;
    }

    /**
     * @param boolean $skip
     */
    public function skip($skip)
    {
        $this->skip = $skip;
    }
}
