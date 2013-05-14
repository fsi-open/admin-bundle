<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Context\DataIO\ExportContextBuilder;
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
        $context = null;

        if (ExportContextBuilder::supports($element)) {
            $contextBuilder = new ExportContextBuilder($element);
            $context = $contextBuilder->buildContext();
        }

        if (!isset($context)) {
            throw $this->createNotFoundException(sprintf('Cant create context for element with id "%s" in export action', $element->getId()));
        }

        $data = $context->getDatasource()->getResult();
        $context->getDatagrid()->setData($data);

        switch ($type) {
            case 'csv':
                $response = new HttpFoundation\CSVExport($context->getDatagrid()->createView(), date('Y_m_d_His'), 200, array(), $this->get('translator'));
                break;
            case 'csvexcel':
                $response =  new HttpFoundation\CSVExcelExport($context->getDatagrid()->createView(), date('Y_m_d_His'), 200, array(), $this->get('translator'));
                break;
            case 'excel':
                $response =  new HttpFoundation\ExcelExport($context->getDatagrid()->createView(), date('Y_m_d_His'), 200, array(), $this->get('translator'));
                break;
            case 'excel2003':
                $response =  new HttpFoundation\Excel2003Export($context->getDatagrid()->createView(), date('Y_m_d_His'), 200, array(), $this->get('translator'));
                break;
            case 'excel2007':
                $response =  new HttpFoundation\Excel2007Export($context->getDatagrid()->createView(), date('Y_m_d_His'), 200, array(), $this->get('translator'));
                break;
        }

        return $response;
    }
}