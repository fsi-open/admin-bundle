<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ResourceController
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var ContextManager
     */
    protected $contextManager;

    function __construct(
        EngineInterface $templating,
        ContextManager $contextManager,
        $resourceActionTemplate
    ) {
        $this->templating = $templating;
        $this->contextManager = $contextManager;
        $this->resourceActionTemplate = $resourceActionTemplate;
    }

    /**
     * @param GenericResourceElement $element
     * @param Request $request
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resourceAction(GenericResourceElement $element, Request $request)
    {
        $context= $this->contextManager->createContext('fsi_admin_resource', $element);

        if (!isset($context)) {
            throw new NotFoundHttpException(sprintf('Cant find context builder that supports element with id "%s"', $element->getId()));
        }

        if (($response = $context->handleRequest($request)) !== null) {
            return $response;
        }

        return $this->templating->renderResponse(
            $context->hasTemplateName() ? $context->getTemplateName() : $this->resourceActionTemplate,
            $context->getData()
        );
    }
}
