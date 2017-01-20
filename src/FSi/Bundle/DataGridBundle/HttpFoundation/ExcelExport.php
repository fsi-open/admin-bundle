<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;

class ExcelExport extends ExportAbstract
{
    /**
     * @var string
     */
    protected $data;

    /**
     * @var string
     */
    protected $fileExtension = 'xls';

    /**
     * @var string
     */
    protected $mimeType = 'application/vnd.ms-excel';

    /**
     * @return ExportAbstract|\Symfony\Component\HttpFoundation\Response
     */
    public function setData()
    {
        $PHPExcel = new \PHPExcel();
        $dataGrid = $this->getDataGrid();
        $rowNum = 1;
        $colNum = 0;

        foreach ($dataGrid->getColumns() as $header) {
            $label =  isset($this->translator)
                ? $this->translator->trans($header->getLabel(), array(), $header->getAttribute('translation_domain'))
                : $header->getLabel();

            $PHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $rowNum, $label);
            $colNum++;
        }

        $rowNum++;

        foreach ($dataGrid as $row) {
            $colNum = 0;
            foreach ($row as $cell) {
                $PHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $rowNum, (string) $cell->getValue());
                $colNum++;
            }

            $rowNum++;
        }

        $writer = $this->getWriter($PHPExcel);
        ob_start();
        $writer->save("php://output");
        $this->data = ob_get_clean();

        return $this->update();
    }

    /**
     * @param \PHPExcel $PHPExcel
     * @return \PHPExcel_Writer_Excel5
     */
    protected function getWriter(\PHPExcel $PHPExcel)
    {
        return new \PHPExcel_Writer_Excel5($PHPExcel);
    }

    /**
     * Update response headers and content;
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function update()
    {
        $fileName = sprintf('%s.%s', $this->getFileName(), $this->fileExtension);
        $this->headers->set('Content-Type', $this->mimeType);
        $this->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');
        return $this->setContent($this->data);
    }
}
