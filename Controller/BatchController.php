<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericBatchElement;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BatchController
{
    /**
     * @param ContextManager $contextManager
     */
    function __construct(
        ContextManager $contextManager
    ) {
        $this->contextManager = $contextManager;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\GenericBatchElement $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function batchAction(GenericBatchElement $element, Request $request)
    {
        $context = $this->contextManager->createContext('fsi_admin_batch', $element);

        if (!isset($context)) {
            throw new NotFoundHttpException(sprintf('Cant find context builder that supports element with id "%s"', $element->getId()));
        }

        $response = $context->handleRequest($request);
        if ($response instanceof Response) {
            return $response;
        } else {
            throw new ContextException("Context which handles batch action must return instance of \\Symfony\\Component\\HttpFoundation\\Response");
        }
    }
}
