<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\HttpFundation;

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
