<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Structure\DoctrineAdminElementInterface;
use FSi\Bundle\DataGridBundle\HttpFoundation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class TreeController extends BaseController
{
    /**
     * @param Request $request
     * @param DoctrineAdminElementInterface $element
     * @param $id
     * @param int $number
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function moveupAction(Request $request, DoctrineAdminElementInterface $element, $id, $number = 1)
    {
        if (!$request->query->has('redirect_uri')) {
            throw $this->createNotFoundException();
        }

        $indexer = $element->getDataIndexer();
        $entity = $indexer->getData($id);
        if (!isset($entity)) {

            return $this->createNotFoundException();
        }

        $result = $element->getRepository()->moveUp($entity, $number);
        if ($result) {
            $element->save($entity);
        }

        return $this->redirect($request->query->get('redirect_uri'));
    }

    /**
     * @param Request $request
     * @param DoctrineAdminElementInterface $element
     * @param $id
     * @param int $number
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function movedownAction(Request $request, DoctrineAdminElementInterface $element, $id, $number = 1)
    {
        if (!$request->query->has('redirect_uri')) {
            throw $this->createNotFoundException();
        }

        $indexer = $element->getDataIndexer();
        $entity = $indexer->getData($id);
        if (!isset($entity)) {
            return $this->createNotFoundException();
        }

        $result = $element->getRepository()->moveDown($entity, $number);
        if ($result) {
            $element->save($entity);
        }

        return $this->redirect($request->query->get('redirect_uri'));
    }
}