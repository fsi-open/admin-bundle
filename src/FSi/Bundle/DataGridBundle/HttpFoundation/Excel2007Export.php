<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;

class Excel2007Export extends ExcelExport
{
    /**
     * @var string
     */
    protected $fileExtension = 'xlsx';

    /**
     * @var string
     */
    protected $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    /**
     * @param \PHPExcel $PHPExcel
     * @return \PHPExcel_Writer_Excel2007|\PHPExcel_Writer_Excel5
     */
    protected function getWriter(\PHPExcel $PHPExcel)
    {
        $writer = new \PHPExcel_Writer_Excel2007($PHPExcel);
        $writer->setPreCalculateFormulas(false);
        return $writer;
    }
}
