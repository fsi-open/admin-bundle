<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AdminController
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $indexActionTemplate;

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param string $indexActionTemplate
     */
    public function __construct(EngineInterface $templating, RouterInterface $router, $indexActionTemplate)
    {
        $this->templating = $templating;
        $this->router = $router;
        $this->indexActionTemplate = $indexActionTemplate;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->templating->renderResponse($this->indexActionTemplate);
    }

    /**
     * @param string $_locale
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function localeAction($_locale, Request $request)
    {
        $request->getSession()->set('admin_locale', $_locale);

        return new RedirectResponse(
            $request->query->has('redirect_uri') ?
                $request->query->get('redirect_uri') :
                $this->router->generate('fsi_admin')
        );
    }
}
