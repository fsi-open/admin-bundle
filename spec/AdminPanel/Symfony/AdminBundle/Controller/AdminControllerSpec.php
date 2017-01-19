<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AdminControllerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    function let($templating, $router)
    {
        $this->beConstructedWith($templating, $router, 'template');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag $requestQuery
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    function it_stores_locale_in_session($request, $session, $requestQuery, $router)
    {
        $request->getSession()->willReturn($session);
        $request->query = $requestQuery;
        $router->generate('fsi_admin')->willReturn('admin_url');

        $session->set('admin_locale', 'qw')->shouldBeCalled();

        $response = $this->localeAction('qw', $request);
        $response->getTargetUrl()->shouldReturn('admin_url');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag $requestQuery
     */
    function it_redirects_to_passed_redirect_uri($request, $session, $requestQuery)
    {
        $request->getSession()->willReturn($session);
        $request->query = $requestQuery;
        $requestQuery->has('redirect_uri')->willReturn(true);
        $requestQuery->get('redirect_uri')->willReturn('uri_to_redirect_to');

        $response = $this->localeAction('qw', $request);
        $response->getTargetUrl()->shouldReturn('uri_to_redirect_to');
    }
}
