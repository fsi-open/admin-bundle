<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\HttpFundation;

use Symfony\Component\HttpFoundation\Response;

class Excel2003Export extends Excel2007Export
{
    /**
     * @param \PHPExcel $PHPExcel
     * @return \PHPExcel_Writer_Excel5
     */
    protected function getWriter(\PHPExcel $PHPExcel)
    {
        $writer = parent::getWriter($PHPExcel);
        $writer->setOffice2003Compatibility(true);
        $writer->setPreCalculateFormulas(false);
        return $writer;
    }
}
