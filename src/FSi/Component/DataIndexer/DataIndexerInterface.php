<?php

/**
 * (c) FSi sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataIndexer;

interface DataIndexerInterface
{
    /**
     * @param mixed $data
     * @return mixed
     */
    public function getIndex($data);

    /**
     * @param string $index
     * @return mixed
     */
    public function getData($index);

    /**
     * @param $indexes
     * @return array
     */
    public function getDataSlice($indexes);

    /**
     * @return string
     */
    public function getSeparator();

    /**
     * Check if data can be indexed by DataIndexer.
     *
     * @param mixed $data
     * @return void
     */
    public function validateData($data);
}
