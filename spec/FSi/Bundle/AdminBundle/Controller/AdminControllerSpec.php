<?php

namespace spec\FSi\Bundle\AdminBundle\Controller;

use PhpSpec\ObjectBehavior;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class AdminControllerSpec extends ObjectBehavior
{
    function let(EngineInterface $templating, RouterInterface $router)
    {
        $this->beConstructedWith($templating, $router, 'template');
    }

    function it_stores_locale_in_session(
        Request $request,
        SessionInterface $session,
        ParameterBag $requestQuery,
        RouterInterface $router
    ) {
        $request->getSession()->willReturn($session);
        $request->query = $requestQuery;
        $router->generate('fsi_admin')->willReturn('admin_url');

        $session->set('admin_locale', 'qw')->shouldBeCalled();

        $response = $this->localeAction('qw', $request);
        $response->getTargetUrl()->shouldReturn('admin_url');
    }

    function it_redirects_to_passed_redirect_uri(
        Request $request,
        SessionInterface $session,
        ParameterBag $requestQuery
    ) {
        $request->getSession()->willReturn($session);
        $request->query = $requestQuery;
        $requestQuery->has('redirect_uri')->willReturn(true);
        $requestQuery->get('redirect_uri')->willReturn('uri_to_redirect_to');

        $response = $this->localeAction('qw', $request);
        $response->getTargetUrl()->shouldReturn('uri_to_redirect_to');
    }
}
