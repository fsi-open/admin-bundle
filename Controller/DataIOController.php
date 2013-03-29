<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\DataGridBundle\HttpFoundation;
use FSi\Bundle\AdminBundle\Structure\AdminElementInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class DataIOController extends BaseController
{
    /**
     * @param AdminElementInterface $element
     * @param $type
     * @return HttpFoundation\CSVExcelExport|HttpFoundation\CSVExport|HttpFoundation\Excel2003Export|HttpFoundation\Excel2007Export|HttpFoundation\ExcelExport
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function exportAction(AdminElementInterface $element, $type)
    {
        if (!$element->hasExportDataGrid() || !$element->hasExportDataSource()) {
            throw $this->createNotFoundException();
        }

        $datasource = $element->getExportDataSource();
        $datagrid = $element->getExportDataGrid();
        $data = $datasource->getResult();
        $datagrid->setData($data);

        switch ($type) {
            case 'csv':
                return new HttpFoundation\CSVExport($datagrid->createView(), date('Y_m_d_His'));
                break;
            case 'csvexcel':
                return new HttpFoundation\CSVExcelExport($datagrid->createView(), date('Y_m_d_His'));
                break;
            case 'excel':
                return new HttpFoundation\ExcelExport($datagrid->createView(), date('Y_m_d_His'));
                break;
            case 'excel2003':
                return new HttpFoundation\Excel2003Export($datagrid->createView(), date('Y_m_d_His'));
                break;
            case 'excel2007':
                return new HttpFoundation\Excel2007Export($datagrid->createView(), date('Y_m_d_His'));
                break;
        }
    }
}