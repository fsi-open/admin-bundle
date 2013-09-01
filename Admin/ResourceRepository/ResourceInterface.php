<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository;

interface ResourceInterface
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return array
     */
    public function getResourceFormOptions();
}