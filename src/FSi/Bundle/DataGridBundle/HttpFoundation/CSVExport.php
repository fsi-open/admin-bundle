<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;

class CSVExport extends ExportAbstract
{
    /**
     * @var string
     */
    protected $enclosure = '"';

    /**
     * @var string
     */
    protected $delimiter = ';';

    /**
     * @var string
     */
    protected $fileExtension = 'csv';

    /**
     * @var string
     */
    protected $mimeType = 'text/csv';

    /**
     * DataGrid as valid csv string
     *
     * @var string
     */
    protected $data;

    /**
     * Set csv delimiter.
     *
     * @param string $delimiter
     * @return CSVExport
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * Set csv strings wrapper.
     *
     * @param $enclosure
     * @return CSVExport
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * Convert DataGridView to csv string.
     *
     * @return ExportAbstract|\Symfony\Component\HttpFoundation\Response
     */
    public function setData()
    {
        $dataGrid = $this->getDataGrid();
        $fp = fopen('php://temp', 'r+');
        // BOM
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        $columns = array();

        foreach ($dataGrid->getColumns() as $column) {
            $columns[] = isset($this->translator)
                ? $this->translator->trans($column->getLabel(), array(), $column->getAttribute('translation_domain'))
                : $column->getLabel();
        }

        fputcsv($fp, $columns, $this->delimiter, $this->enclosure);

        foreach ($dataGrid as $row) {
            $rowArray = array();
            foreach ($row as $cell) {
                $rowArray[] = $cell->getValue();
            }

            fputcsv($fp, $rowArray, $this->delimiter, $this->enclosure);
        }

        rewind($fp);
        $this->data = stream_get_contents($fp);
        $this->data = $this->setLineEndings($this->data);
        return $this->update();
    }

    /**
     * Change line ending character in child class,
     * which is \n (LF) by default.
     */
    public function setLineEndings($data)
    {
        // Do nothing by default.
        return $data;
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
