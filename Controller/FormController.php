<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class FormController
{
    /**
     * @param EngineInterface $templating
     * @param ContextManager $contextManager
     * @param string $formActionTemplate
     */
    function __construct(
        EngineInterface $templating,
        ContextManager $contextManager,
        $formActionTemplate
    ) {
        $this->templating = $templating;
        $this->contextManager = $contextManager;
        $this->formActionTemplate = $formActionTemplate;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\FormElement $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function formAction(FormElement $element, Request $request)
    {
        $context = $this->contextManager->createContext('fsi_admin_form', $element);

        if (!isset($context)) {
            throw new NotFoundHttpException(sprintf('Cant find context builder that supports element with id "%s"', $element->getId()));
        }

        if (($response = $context->handleRequest($request)) !== null) {
            return $response;
        }

        return $this->templating->renderResponse(
            $context->hasTemplateName() ? $context->getTemplateName() : $this->formActionTemplate,
            $context->getData()
        );
    }
}
