<?php

namespace AdminPanel\Symfony\AdminBundle\HttpFundation;

class CSVExcelExport extends CSVExport
{
    /**
     * Set CRLF line ending
     * @param $data
     * @return mixed
     */
    public function setLineEndings($data)
    {
        $data = str_replace("\n", "\r\n", $data);

        return $data;
    }
}
