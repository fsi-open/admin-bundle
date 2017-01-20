<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Data;

use FSi\Component\DataGrid\Tests\Fixtures\Entity;
use FSi\Component\DataGrid\Data\DataRowset;
use InvalidArgumentException;

class DataRowsetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateWithInvalidData()
    {
        $rowset = new DataRowset('Invalid Data');
    }

    public function testCreateRowset()
    {
        $e1 = new \stdClass();
        $e2 = new \stdClass();
        $e1->name = 'entity2';
        $e2->name = 'entity2';

        $data = array(
            'e1' => $e1,
            'e2' => $e2
        );

        $rowset = new DataRowset($data);

        foreach ($rowset as $index => $row) {
            $this->assertSame($data[$index], $row);
        }

        $this->assertCount(2, $rowset);
    }
}
