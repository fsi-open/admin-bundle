<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Data;

use FSi\Component\DataGrid\DataMapper\DataMapperInterface;

interface IndexingStrategyInterface
{
    /**
     * Set separator used to implode composite indexes.
     *
     * @param string $separator
     */
    public function setSeparator($separator);

    /**
     * Method should return unique index for passed object.
     *
     * @param mixed $object
     * @param \FSi\Component\DataGrid\DataMapper\DataMapperInterface $dataMapper
     * @return string|null if method can't return index for object it returns null value
     *                     in other case it should return string imploded with separator value.
     */
    public function getIndex($object, DataMapperInterface $dataMapper);

    /**
     * Restore index to original data.
     *
     * @param string $index
     * @param mixed $dataType
     * @return array
     */
    public function revertIndex($index, $dataType);
}
